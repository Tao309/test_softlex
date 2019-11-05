<?php

/**
 * Class NotificationService
 * @property string $text
 */
class NotificationService
{
	private $text;

	public function setText(string $text):void
	{
		$this->text = $text;
	}

	public function notify(User $user):void
	{
		new EmailNotificator($user, $this->text);
		new SmsNotificator($user, $this->text);
		new WebNotificator($user, $this->text);
	}
}

/**
 * Interface NotifcatorInterface
 */
interface NotifcatorInterface
{
	public function getText();
	public function getUser();
}

/**
 * Class Notificator
 * @property User $user
 * @property string $text
 */
abstract class Notificator implements NotifcatorInterface
{
	private $user;
	private $text;

	abstract protected function send():void;

	public function __construct(User $user, string $text)
	{
		$this->user = $user;
		$this->text = $text;

		$this->send();
	}

	public function getText():string
	{
		return $this->text;
	}

	public function getUser():User
	{
		return $this->user;
	}
}

class EmailNotificator extends Notificator
{
	protected function send(): void
	{
		//..Sending
		$text = $this->getText();
		$email = $this->getUser()->email;
	}
}

class SmsNotificator extends Notificator
{
	protected function send(): void
	{
		//..Sending
		$text = $this->getText();
		$phone = $this->getUser()->phone;
	}
}

class WebNotificator extends Notificator
{
	protected function send(): void
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