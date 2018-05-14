<?php
namespace SashaMart\CartDiscount;

use phpDocumentor\Reflection\DocBlock\Tags\Reference\Reference;

class Cart
{
    const PENSION_PERCENT = 0.05;
    const SUM_OVERFLOW = 10000;
    const DISCOUNT_OVERFLOW = 500;
    const DISCOUNT_FIRST = 100;

	private $_user;

	private $_items = [];
	private $_db;

	public function __construct(?User $user)
	{
		$this->_user = $user;
        $dbInstance = DbConnection::getInstance();
        $this->_db = $dbInstance->getConnection();
	}

	public function getUser(): ?User
	{
		return $this->_user;
	}

	// item_id, price, sku, etc.

    /**
     * @param array $item
     */
    public function addItem(array $item): void
	{
		$this->_items[] = $item;
	}

    public function getTotalAmount(): int
	{
		$ret = 0;
		foreach ($this->_items as $item)
			$ret += $item['price'];
		return $ret;
	}

	public function getDiscountedTotalAmount(): float
	{
		return $this->getTotalAmount() - $this->_getDiscount();
	}

	private function _getDiscount(): float
	{
	    $result = 0;
	    $total = $this->getTotalAmount();

	    if (
	        $this->_user->getSex() === 'm' && $this->_user->getAge() >= 60 ||
            $this->_user->getSex() === 'w' && $this->_user->getAge() >= 55
        )
            $result += $total * self::PENSION_PERCENT;

	    if ($total >= self::SUM_OVERFLOW)
	        $result += self::DISCOUNT_OVERFLOW;

        if ($this->_isFirstOrder())
            $result += self::DISCOUNT_FIRST;

        $result = round($result, 2);
        $percent = $result / $total;
        $control = 0;
        foreach ($this->_items as $key=>$item) {
	        $this->_items[$key]['discount'] = round($item['price'] * $percent, 2);
	        $control += $this->_items[$key]['discount'];
        }
        // если сумма всех скидок, добавленных к товарам, не равна итоговой скидке
		// то добавим разницу к первому товару
        if ($control !== $result && isset($this->_items[0])) {
			$this->_items[0]['discount'] += $result - $control;
        }

		return $result;
	}

	private function _isFirstOrder(): bool
    {
        $stnt = $this->_db->prepare( "SELECT count(*) FROM orders WHERE user_id=:id");
        $stnt->bindValue(':id', $this->_user->id);
        $count = $stnt->execute();
        $res = $count->fetchArray();

        return $res['count(*)'] > 0 ? false : true;
    }

	public function addOrder()
    {
        $stnt = $this->_db->prepare("INSERT INTO orders(items, sum, sum_with_discount, user_id) "
            ."VALUES (:items, :sum, :sum_with_discount, :id)");
        $stnt->bindValue(':items', json_encode($this->_items), SQLITE3_TEXT);
        $stnt->bindValue(':sum', $this->getTotalAmount());
        $stnt->bindValue(':sum_with_discount', $this->getDiscountedTotalAmount());
        $stnt->bindValue(':id', $this->_user->id);

        $stnt->execute();
    }
}