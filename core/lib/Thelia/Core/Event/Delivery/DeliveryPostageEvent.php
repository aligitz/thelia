<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/


namespace Thelia\Core\Event\Delivery;

use Thelia\Core\Event\ActionEvent;
use Thelia\Core\Translation\Translator;
use Thelia\Model\Address;
use Thelia\Model\Cart;
use Thelia\Model\Country;
use Thelia\Model\OrderPostage;
use Thelia\Model\State;
use Thelia\Module\AbstractDeliveryModule;
use Thelia\Module\BaseModuleInterface;

/**
 * Class DeliveryPostageEvent
 * @package Thelia\Core\Event\Delivery
 * @author Julien Chanséaume <julien@thelia.net>
 */
class DeliveryPostageEvent extends ActionEvent
{
    /** @var BaseModuleInterface */
    protected $module = null;

    /** @var Cart */
    protected $cart = null;

    /** @var Address */
    protected $address = null;

    /** @var Country */
    protected $country = null;

    /** @var State */
    protected $state = null;

    /** @var bool */
    protected $validModule = false;

    /** @var OrderPostage|null */
    protected $postage = null;

    /**
     * @var \DateTime|null
     */
    protected $deliveryDate = null;

    /**
     * @var string
     */
    protected $deliveryMode = null;

    /** @var array */
    protected $additionalData = [];

    /**
     * DeliveryPostageEvent constructor.
     * @param $module
     * @param Cart $cart
     * @param Address|null $address
     * @param Country $country
     * @param State $state
     */
    public function __construct(
        $module,
        Cart $cart,
        Address $address = null,
        Country $country = null,
        State $state = null
    ) {
        $this->module = $module;
        $this->cart = $cart;
        $this->address = $address;
        $this->country = $country;
        $this->state = $state;
    }

    /**
     * @return Cart
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * @param Cart $cart
     * @return DeliveryPostageEvent
     */
    public function setCart($cart)
    {
        $this->cart = $cart;
        return $this;
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param Address $address
     * @return DeliveryPostageEvent
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDeliveryDate()
    {
        return $this->deliveryDate;
    }

    /**
     * @param \DateTime|null $deliveryDate
     * @return DeliveryPostageEvent
     */
    public function setDeliveryDate($deliveryDate)
    {
        $this->deliveryDate = $deliveryDate;
        return $this;
    }

    /**
     * @return AbstractDeliveryModule
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @param AbstractDeliveryModule $module
     * @return DeliveryPostageEvent
     */
    public function setModule($module)
    {
        $this->module = $module;
        return $this;
    }

    /**
     * @return null|OrderPostage
     */
    public function getPostage()
    {
        return $this->postage;
    }

    /**
     * @param null|double|OrderPostage $postage
     * @return DeliveryPostageEvent
     */
    public function setPostage($postage)
    {
        $this->postage = OrderPostage::loadFromPostage($postage);
        return $this;
    }

    /**
     * @return boolean
     */
    public function isValidModule()
    {
        return $this->validModule;
    }

    /**
     * @param boolean $validModule
     * @return DeliveryPostageEvent
     */
    public function setValidModule($validModule)
    {
        $this->validModule = $validModule;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasAdditionalData()
    {
        return \count($this->additionalData) > 0;
    }

    /**
     * @return array
     */
    public function getAdditionalData()
    {
        return $this->additionalData;
    }

    /**
     * @param array $additionalData
     * @return DeliveryPostageEvent
     */
    public function setAdditionalData($additionalData)
    {
        $this->additionalData = $additionalData;
        return $this;
    }

    /**
     * @param string $key the key of the additional data
     * @param mixed $value the value of the additional data
     *
     * @return DeliveryPostageEvent
     */
    public function addAdditionalData($key, $value)
    {
        $this->additionalData[$key] = $value;

        return $this;
    }

    /**
     * @return Country|null
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getCountry()
    {
        return $this->getAddress() !== null ? $this->getAddress()->getCountry() : $this->country;
    }

    /**
     * @return State|null
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getState()
    {
        return $this->getAddress() !== null ? $this->getAddress()->getState() : $this->state;
    }

    /**
     * @return mixed
     */
    public function getDeliveryMode()
    {
        return $this->deliveryMode;
    }

    /**
     * @param mixed $deliveryMode
     *
     * @return DeliveryPostageEvent
     * @throws \Exception
     */
    public function setDeliveryMode($deliveryMode)
    {
        if (!in_array($deliveryMode, ['pickup', 'delivery'])) {
            throw new \Exception(Translator::getInstance()->trans('A delivery module can only de of type pickup or delivery'));
        }

        $this->deliveryMode = $deliveryMode;
        return $this;
    }
}
