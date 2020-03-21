<?php


class Goods
{
    public $name = 'GTX 2080';
    public $price = '6999￥';

    public function goods()
    {
        return $play = "提高学习/工作效率";
    }
}

$gd = new Goods();
echo '商品名：' . ($gd->name) . '<br>';
echo '商品价格：' . ($gd->price) . '<br>';
echo '商品价值：' . ($gd->goods()) . '<br>';