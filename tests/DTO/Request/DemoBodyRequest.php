<?php

namespace App\DTO\Request;

use Tang\ApiDocs\Annotation\ApiModelProperty;
use Tang\DTO\Contracts\RequestBody;

class DemoBodyRequest implements RequestBody
{

    /**
     * @ApiModelProperty(value="demo名称")
     */
    public ?string $demoName = null;

    /**
     * @ApiModelProperty(value="价格",required=true)
     */
    public float $price;

    /**
     * @ApiModelProperty(value="示例id",required=true)
     * @var int[]
     */
    public array $demoId;

}