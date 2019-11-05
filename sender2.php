<?php

interface Service
{
	public function send(): void;
	public function setText(string $text): void;
}


/**
 * Class NotificationService
 * @property string $text
 * @property Service $notificator
 * @property User $user
 */
class NotificationService
{
	private $text;
	private $user;

	public function __construct($user)
	{
		$this->user = $user;
	}

	private function sendByMethod(Service $notificator):void
	{
		$notificator->setText($this->text);
		$notificator->send();
	}
	public function setText(string $text):void
	{
		$this->text = $text;
	}

	public function getText():string
	{
		return $this->text;
	}

	public function getUser():User
	{
		return $this->user;
	}

	public function notify(User $user):void
	{
		$this->sendByMethod(new EmailNotificator($this->user));

		$this->sendByMethod(new SmsNotificator($this->user));

		$this->sendByMethod(new WebNotificator($this->user));
	}
}

class EmailNotificator extends NotificationService implements Service
{
	public function send(): void
	{
		//..Sending
		$text = $this->getText();
		$email = $this->getUser()->email;
	}
}

class SmsNotificator extends NotificationService implements Service
{
	public function send(): void
	{
		//..Sending
		$text = $this->getText();
		$phone = $this->getUser()->phone;
	}
}

class WebNotificator extends NotificationService implements Service
{
	public function send(): void
	{
		//..Sending
		$text = $this->getText();
		$user = $this->getUser();
	}
}


$service = new NotificationService();
$service->setText('Какой-то текст');

$users = [];
foreach ($users as $user) {
	$service->notify($user);
}