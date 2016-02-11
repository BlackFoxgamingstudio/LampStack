<?php

class Animal{
	var $name, $health;

	function __construct($name,$health){
		$this->health = 100;
		$this->name = $name;
	}

	function walk(){
		$this->health = $this->health - 1;
	}
	function run(){
		$this->health = $this->health - 5;
	}
	function displayHealth(){
		echo "<br />Name: " . $this->name;
		echo "<br />Health: " . $this->health ."<br />";
	}
}

class Dog extends Animal {
	function __construct($name){
		parent::__construct($name);
		$this->health = 150;
	}

	function pet(){
		$this->health = $this->health + 5;
	}
}

class Dragon extends Animal{
	function __construct($name){
		parent::__construct($name);
		$this->health = 170;
	}

	function fly(){
		$this->health = $this->health - 10;
	}

	function displayHealth(){
		echo "<br />This is a dragon!<br/>";
		parent::displayHealth();
	}
}

$animal1 = new Animal("Bob");
$animal1->walk();
$animal1->walk();
$animal1->walk();
$animal1->run();
$animal1->run();
$animal1->displayHealth();

$dog1 = new Dog("Steve");
$dog1->walk();
$dog1->walk();
$dog1->walk();
$dog1->run();
$dog1->run();
$dog1->displayHealth();

$dragon1 = new Dragon("Blue Dragon");
$dragon1->walk();
$dragon1->walk();
$dragon1->walk();
$dragon1->run();
$dragon1->run();
$dragon1->fly();
$dragon1->fly();
$dragon1->displayHealth();

?>