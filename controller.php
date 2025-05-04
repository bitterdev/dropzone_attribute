<?php

namespace Concrete\Package\DropzoneAttribute;

use Bitter\DropzoneAttribute\Provider\ServiceProvider;
use Concrete\Core\Database\EntityManager\Provider\ProviderAggregateInterface;
use Concrete\Core\Database\EntityManager\Provider\StandardPackageProvider;
use Concrete\Core\Entity\Package as PackageEntity;
use Concrete\Core\Package\Package;

class Controller extends Package implements ProviderAggregateInterface
{
    protected string $pkgHandle = 'dropzone_attribute';
    protected string $pkgVersion = '0.0.2';
    protected $appVersionRequired = '9.0.0';
    protected $pkgAutoloaderRegistries = [
        'src/Bitter/DropzoneAttribute' => 'Bitter\DropzoneAttribute',
    ];

    public function getPackageDescription(): string
    {
        return t('Add a drag & drop file upload field anywhere attributes are supported in Concrete CMS.');
    }

    public function getPackageName(): string
    {
        return t('Dropzone Attribute ');
    }
    public function getEntityManagerProvider(): StandardPackageProvider
    {
        $locations = [
            'src/Bitter/DropzoneAttribute/Entity' => 'Bitter\DropzoneAttribute\Entity'
        ];

        return new StandardPackageProvider($this->app, $this, $locations);
    }

    public function on_start()
    {
        /** @var ServiceProvider $serviceProvider */
        /** @noinspection PhpUnhandledExceptionInspection */
        $serviceProvider = $this->app->make(ServiceProvider::class);
        $serviceProvider->register();
    }

    public function install(): PackageEntity
    {
        $pkg = parent::install();
        $this->installContentFile("data.xml");
        return $pkg;
    }

    public function upgrade()
    {
        parent::upgrade();
        $this->installContentFile("data.xml");
    }
}