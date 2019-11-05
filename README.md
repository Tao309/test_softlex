## ЗАДАНИЕ 1

Есть сервис для отправки уведомлений

class NotificationService
{
   public function notify(User $user, $text)
   {
       $emailNotificator = new EmailNotificator();
       $smsNotificator = new SmsNotificator();

       $emailNotificator->sendEmail($user->email, $text);
       $smsNotificator>sendSms($user->phone, $text);
   } 
}

class EmailNotificator 
{
   public function sendEmail($email, $text) : void
   { ... }
}

class SmsNotificator
{
   public function sendSms($phone, $text): void 
   { ... }
}

Этот сервис сконфигурирован и отдан в клиентский код для выполнения рассылки

// Инициализация и конфигурация сервиса
$service = new NotificationService();

// Клиентский код с доступом к готовому к работе объекту сервиса рассылки
$text = 'Какой-то текст';

foreach ($users as $user) {
   $service->notify($user, $text);
}

	Какие принципы SOLID нарушены в проектировании сервиса отправки уведомлений
	Какие паттерны проектирования можно использовать, чтобы сделать сервис более гибким и способным к легкому расширению способов рассылки
	Какие еще проблемы есть в этом коде

Необходимо сделать рефакторинг сервиса, чтобы была возможность добавить третий способ отправки уведомления. Например, WebPushNotificator




## ЗАДАНИЕ 2

Есть класс User для таблицы users. 
Поля таблицы:
id
name
boss_id (ссылается на users.id)

Реализация класса не имеет значения, методы в класс можно добавлять произвольно по необходимости. 
Это может быть, например, реализация Active Record из фреймворка Yii2

Необходимо обойти всё дерево подчиненных любого уровня от выбранного босса и вывести их имя.

