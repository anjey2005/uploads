## Uploads

Проект на Laravel. Делался как пример для портфолио. Суть проекта особой роли не играет - старался использовать как можно
больше возможностей Laravel<br>
<br>


При разработке пользовался:<br>
- среда PhpStorm + встроенный в PHP веб-сервер<br>
- само собой php-фреймворк Laravel 9 и доп пакеты (laravel-debugbar, ui, image, laravel-websockets)<br>
- контроль версий Git<br>
- Composer<br>
- Php<br>
- MySql<br>
- Node для компиляция JS и CSS в Vite (если нет надобности в компиляции - можно не ставить)<br>
- Docker
  <br>


Получилось задействовать из возможностей Laravel:<br>
- стартовый комплект Laravel: Аутентификация + Авторизация + Верификация email + Сброс пароля<br>
- маршрутизация + базовые посредники для аутентификации + ограничение частоты запросов<br>
- контроллеры, HTTP-запросы/ответы, шаблоны Blade, сессии, валидация, CSRF защита<br>
- работа с базой данных через ORM-библиотеку Eloquent<br>
- компиляция JS и CSS через Vite, особое внимание фронтенду не уделялось (к установленному node добавлены пакеты: jQuery,
  Bootstrap, Laravel-echo, Pusher-js)<br>
- прослушивание модели - отслеживание событий создания и удаления<br>
- широковещание - трансляция событий по приватному каналу для отправки сообщений пользователю, использовался бесплатный Laravel-echo<br>
- события - для отправки сообщений пользователю<br>
- очередь - для отправки сообщений пользователю<br>
- в коде применялся функционал Laravel: коллекции, помощники, фасады<br>
  <br>


По коду:<br>
- авто форматирование PhpStorm + немного коментов (вроде ничего сложного не делал)<br>
- проект мелкий, много не покажешь, удалось реализовать порождающий патерн "Фабричный метод"<br>
  <br>


По сути проекта:<br>
Обмен и хранение четырех категорий файлов: картинки, видео, документы и архивы. Для удобства файлы можно просматривать по
категориям и с сортировкой: по дате размещения, количеству лайков и скачиваний.<br>
Пользователи не прошедшие авторизацию могут просматривать, скачивать и ставить лайки файлам. Для них установлен лимит по IP
на количество скачиваний и лайков.<br>
Зарегистрированным пользователям разрешено закачивать (в публичном или приватном режиме просмотра), предоставлять доступ любому
пользователю посредством ссылки на страницу файла (напрямую ссылки ставить нельзя, только через сайт) включая приватные файлы,
удалять свои загруженные файлы, ставить лайки другим файлам и скачивать любые доступные файлы (публичные или по ссылке от
других пользователей). Для авторизованных пользователей также установлен лимит на количество скачиваний и лайков. Можно сменить
текущий пароль.<br>
<br>


Требования для сборки и работы проекта (npm и node - если надо собирать js и css, в противном случае не нужны):<br>
- git (или качать в ручную)<br>
- Composer (для установки пакетов)<br>
- npm<br>
- Php >=8.0 (требования Laravel)<br>
- node >=14.18 лучше 16 (требования Vite)<br>
- web-server<br>
- база данных<br>
- почта (по дефолту пишется в лог фаил)<br>
  (для тестов сразу создано 3 пользователя, логин и пароль совпадают: admin@admin.com, user1@user.com, user2@user.com)<br>
  <br>


Установка (в среде для запуска уже должен быть установлен Web-сервер + Php + База + Npm + Node):<br>
так как в проекте выложены скомпилированные js и css, при желании можно не подымать node и npm а использовать их<br>
- копируем проект<br>
git clone https://github.com/anjey2005/exampl_public.git<br>

- настройка .env (приложил свой .env.local, останется подправить адрес и базу или как есть скопировать)<br>

- по очередно<br>
composer install<br>
php artisan key:generate<br>
php artisan storage:link<br>
php artisan migrate --seed<br>

- если надо собирать js и css<br>
npm install<br>
  <br>


Запуск:<br>
- Web-serverver (для использования встроенного) <br>
php artisan serv &<br>

- очередь Laravel<br> 
php artisan queue:work &<br>

- вебсокеты Laravel<br>
php artisan websockets:serve &<br>

- Node (если надо собирать js и css)<br>
npm run dev<br>

- открываем указанный в .env адрес, если использовался встроенный web-server<br>
  <br>


PS: При загрузке файлов в очереди стоит задержка в 3 сек (что бы страница успевала перегрузиться до оповещения) - можно убрать<br>
фаил: app/Observers/UploadObserver.php<br>
строка: ApproveUploadJob::dispatch($upload)->delay(now()->addSecond(3));<br> 
  <br>


PS2: И что бы почти ничего не делать выкладываю файлы для докера, минимум Docker должен быть установлен и есть права<br>
- установка<br>
  выбрать папку куда все будет установлено и в ней запустить команду ниже или ручками скачать и распаковать проект <br>
git clone https://github.com/anjey2005/exampl_public.git ./<br>
  запускать команды по очереди:<br>
chmod 777 `find ./storage ./bootstrap/cache -type d`<br>
cp .env.db .env<br>
docker-compose up -d<br>
docker exec -u root:root uploads_php composer install<br>
docker exec -u root:root uploads_php php artisan key:generate<br>
docker exec -u root:root uploads_php php artisan storage:link<br>
docker exec uploads_php php artisan migrate --seed<br>
docker exec uploads_php php artisan queue:work &<br>
docker exec uploads_php php artisan websockets:serve &<br>
http://127.0.0.1:8002<br>
  <br>

- остановить все это<br>
docker stop uploads_php uploads_nginx uploads_db<br>
  <br>

- запуск после установки<br>
docker-compose up -d<br>
docker exec uploads_php php artisan queue:work &<br>
docker exec uploads_php php artisan websockets:serve &<br>
http://127.0.0.1:8002<br>
