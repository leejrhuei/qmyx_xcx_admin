<?php

namespace app\api\model;

class ProductProperty extends BaseModel
{
    protected $hidden = ['id', 'product_id', 'update_time', 'delete_time'];
}
