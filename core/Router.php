<?php

class Router {
    
    //@var_string
    private $route;
    
    //@var_array
    private $routes;
    
    //CONSTRUCTOR
    public function __construct() 
    {
        //Присваиваем массив с роутами в локальную переменную класса
        $this->routes = include ('./setting/routing.php');
        // Присваиваем строку запроса пользователя в локальную переменную класса  (после домена)
        $this->route = $_GET['route'];
    }
    
    //ROUTER
    public function route() 
    {
         //Если есть путь то разбираем его по слешу
         if ($this->route)
         {      
             //разбиваем url по слешу
             $dataController = $this->getPatch();

               // проверяем если в массиве с роутами данный контроллер        
            if(isset($this->routes[$dataController['name']]))
            { 
                //подключаем контроллер
                $this->showController($dataController);             
            }
            else 
            {
                $this->getError(404, 'invalid route');
            }
          }
        }
        
        private function showController($data){
            //вся информация о роуте
            $session = [];
            $route = $this->routes[$data['name']];
            //записываем метод запроса
            $method = @$_SERVER['REQUEST_METHOD'];
            $header = getallheaders();

            if(isset($header['Authorization'])){
                include './controller/TokenController.php';
                $token_manager = new TokenController($header['Authorization']);
                //если токен актуальный то заполняется глобальную переменную
                //2 параметра id пользователя и его roles
                //и сразу перенесем в локальную переменную
                $session = $GLOBALS['session'] = $token_manager->getPermission();
            }

            //если в роутах нет совпадения по роуту и методу запроса выходим их программы
            if(!isset($route['methods'][$method])){
                $this->getError(404,'not valid patch or method');
            }

            //проверяем права доступа к роутам
            //смотрим требуются ли у нас вообще права в роуте если не нужны то проходим ничего не проверяя
            //пока что такой тоут только login
            //может и кастфльная аутентификация на скорую руку ничего лучше не придумал)
            if($route['permission'] != ''){
                //если у нас права администратора то пропускаем ко всему
                if($session['roles'] != 'administrator'){
                    if($session['roles'] != $route['permission']){
                        $this->getError(403,'access denied');
                    }
                }
            }


            //далее вытягиваем данные о методе класса и слуг если есть
            $infoController = $route['methods'][$method];

            //проверяем нужен ли слуг по запрашиваемому методу и передали ли нам его
            if(isset($infoController['slug']) || isset($data['slug'])){
                //проверяем соответствует ли слуг шаблону
                if(preg_match($infoController['slug'],$data['slug'])){
                    //если все хорошо конектим нужный контроллер создаём экземпляр класса и вызываем нужный метод и передаём слуг
                    include $route['patch'];
                    $Action = $infoController['action'];
                    $Controller = new $route['controller']();
                    $Controller->$Action($data['slug']);
                } else {
                    $this->getError(404,'Parameters do not match pattern');
                }
            } else {

                $Action = $infoController['action'];

                include $route['patch'];
                $Controller = new $route['controller']();
                $Controller->$Action();
            }

        }
        
        //Метод для получения названия контроллера и его параметра из запроса пользователя
        //@return_array
        
        private function getPatch() 
        {
            $result = [];
            // разбераем ройт по слешу            
            $pach_routes = explode('/', $this->route);
            
            //Получаем название контроллера
            $result['name'] = $pach_routes[0];
            //получаем параметр контроллера
            if(isset($pach_routes[1]))
            {
                $result['slug']=$pach_routes[1];
            }
            return $result;
        } 
        private function getError($code,$date)
        {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(array(
                'code'=>$code,
                'error'=>$date
            ));
            die();
        }
}

