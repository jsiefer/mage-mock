# mage-mock

Creating UnitTests for Magento classes can be hard considering that you have to
extend from so many classes which in term require a lot of setup to get initialised in the first place.

The framework mocker will help you to mock the Magento environment.

The idea for a UnitTest here is that it...

* ...should run quickly (<100ms)
* ...no Magento installation or source is required

All required Magento Classes are generated on the fly by the class-mocker.


## Setup

You load this project from composer.

    composer require jsiefer/mage-mock


Create a PHPUnit bootstrap.php file and register the MagentMock to the ClassMocker and enable the ClassMocker.

    $mocker = new ClassMocker();
    $mocker->mockFramework(new MagentoMock());
    $mocker->enable();


It is also recommended to setup the ClassMocker test listener so ClassMocks are validated as well.
Just add listener to your phpunit.xml

    <listeners>
        <listener class="JSiefer\ClassMocker\TestListener" />
    </listeners>


## Note
This is still an early release and a proof of concept. It yet needs to be tested if this approach can be of use.

