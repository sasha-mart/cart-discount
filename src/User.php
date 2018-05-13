<?php
namespace SashaMart\CartDiscount;

class User
{
    public $id;
    public $_age;
    public $_sex;

    public function __construct(int $id, int $age, string $sex)
    {
        $this->id = $id;
        $this->_age = $age;
        $this->_sex = $sex;
    }

	public function getAge(): int
    {
		return $this->_age;
	}

	public function getSex(): string
    {
        return $this->_sex;
    }
}