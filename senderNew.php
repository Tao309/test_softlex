<?php

/*
	Какие принципы SOLID нарушены в проектировании сервиса отправки уведомлений
- Отправка через методы sendEmail, sendSms лучше сделать таким методом, который будет вызываться в главном классе,
а именно реализация в дочерних => send(). Таким образом сделаем подстановку метода.
- Чтобы отправить смс нужен именно класс SmsNotificator (также и email), который не может расширяться по настройкам (текст
и email юзера), и всегда имеет свои данные, когда их можно сделать общими
- Я бы ещё создал класс для формирования контента и отдельный для отправки с обработкой этого контента

	Какие паттерны проектирования можно использовать, чтобы сделать сервис более гибким и способным
 к легкому расширению способов рассылки
- Добавил синглтон контейнер, для вызова в последующем нужных классов (смс, email)
- Стратегия или асбтрактная фабрика, для получения общих данных в одном месте, а реализация в нужном
- Делегирования - выполнение метода send()

	Какие еще проблемы есть в этом коде
- Общие данные (пользователь, текст) должны быть заранее записаны и браться с одного метода, не инициализироваться
каждый раз
- Очень сложное расширение функционала, когда добавляем отправку через web, много лишнего повторяющегося кода


 */

interface Service
{
	public function notify(): void;
}

class NotificationService
{
	const WEB = 'web';
	const SMS = 'sms';
	const EMAIL = 'email';

	private $user;
	private $text;

	private $emailNotificator;
	private $smsNotificator;
	private $webPushNotificator;

	public function __construct()
	{
		$this->init();
	}

	private function init()
	{
		$this->emailNotificator = new EmailNotificator();
		$this->smsNotificator = new SmsNotificator();
		$this->webPushNotificator = new WebPushNotificator();
	}

	protected function getText(): string
	{
		return $this->text;
	}

	protected function getUser()
	{
		return $this->user;
	}

	public function setText(string $text)
	{
		$this->text = $text;
	}

	public function setUser(User $user)
	{
		$this->user = $user;
	}

	public function getPushType(string $type):NotificationService
	{
		switch($type)
		{
			case self::WEB:
				return $this->webPushNotificator;
				break;
			case self::SMS:
				return $this->smsNotificator;
				break;
		}

		return $this->emailNotificator;
	}
}

class EmailNotificator extends NotificationService implements Service
{
	public function notify(): void
	{
		$text = $this->getText();
		$user = $this->getUser()->email;
	}
}

class SmsNotificator extends NotificationService implements Service
{
	public function notify(): void
	{
		$text = $this->getText();
		$user = $this->getUser()->phone;
	}
}

class WebPushNotificator extends NotificationService implements Service
{
	public function notify(): void
	{
		$text = $this->getText();
		$user = $this->getUser();
	}
}

$service = new NotificationService();
$service->setText('Какой-то текст');

$users = [];
foreach ($users as $user) {
	$service->setUser($user);
	$service->getPushType('web')->notify();
}