<?php

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
	{

	}
}

class SmsNotificator
{
	public function sendSms($phone, $text): void
	{

	}
}

//Этот сервис сконфигурирован и отдан в клиентский код для выполнения рассылки

// Инициализация и конфигурация сервиса
$service = new NotificationService();

// Клиентский код с доступом к готовому к работе объекту сервиса рассылки
$text = 'Какой-то текст';

$users = [];
foreach ($users as $user) {
	$service->notify($user, $text);
}