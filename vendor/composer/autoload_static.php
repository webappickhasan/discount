<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9fe709def937f5a07f9c3f81a821ac31
{
    public static $files = array (
        '75224de648373daee048deff719e279d' => __DIR__ . '/..' . '/inpsyde/assets/inc/functions.php',
        'd57dd50c5392c5a5044aae288d38e1c5' => __DIR__ . '/..' . '/inpsyde/assets/inc/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Micropackage\\Requirements\\' => 26,
            'Micropackage\\Internationalization\\' => 34,
        ),
        'I' => 
        array (
            'Inpsyde\\Assets\\' => 15,
            'Inpsyde\\' => 8,
        ),
        'D' => 
        array (
            'Disco\\Rest\\' => 11,
            'Disco\\Internals\\' => 16,
            'Disco\\Integrations\\' => 19,
            'Disco\\Frontend\\' => 15,
            'Disco\\Engine\\' => 13,
            'Disco\\Cli\\' => 10,
            'Disco\\Backend\\' => 14,
            'Disco\\App\\' => 10,
            'Disco\\Ajax\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Micropackage\\Requirements\\' => 
        array (
            0 => __DIR__ . '/..' . '/micropackage/requirements/src',
        ),
        'Micropackage\\Internationalization\\' => 
        array (
            0 => __DIR__ . '/..' . '/micropackage/internationalization/src',
        ),
        'Inpsyde\\Assets\\' => 
        array (
            0 => __DIR__ . '/..' . '/inpsyde/assets/src',
        ),
        'Inpsyde\\' => 
        array (
            0 => __DIR__ . '/..' . '/inpsyde/wp-context/src',
        ),
        'Disco\\Rest\\' => 
        array (
            0 => __DIR__ . '/../..' . '/rest',
        ),
        'Disco\\Internals\\' => 
        array (
            0 => __DIR__ . '/../..' . '/internals',
        ),
        'Disco\\Integrations\\' => 
        array (
            0 => __DIR__ . '/../..' . '/integrations',
        ),
        'Disco\\Frontend\\' => 
        array (
            0 => __DIR__ . '/../..' . '/frontend',
        ),
        'Disco\\Engine\\' => 
        array (
            0 => __DIR__ . '/../..' . '/engine',
        ),
        'Disco\\Cli\\' => 
        array (
            0 => __DIR__ . '/../..' . '/cli',
        ),
        'Disco\\Backend\\' => 
        array (
            0 => __DIR__ . '/../..' . '/backend',
        ),
        'Disco\\App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
        'Disco\\Ajax\\' => 
        array (
            0 => __DIR__ . '/../..' . '/ajax',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Disco\\Ajax\\Ajax' => __DIR__ . '/../..' . '/ajax/Ajax.php',
        'Disco\\Ajax\\Ajax_Admin' => __DIR__ . '/../..' . '/ajax/Ajax_Admin.php',
        'Disco\\App\\Attributes\\AttributeFactory' => __DIR__ . '/../..' . '/app/Attributes/AttributeFactory.php',
        'Disco\\App\\Attributes\\Cart' => __DIR__ . '/../..' . '/app/Attributes/Cart.php',
        'Disco\\App\\Attributes\\Customer' => __DIR__ . '/../..' . '/app/Attributes/Customer.php',
        'Disco\\App\\Attributes\\CustomerHistory' => __DIR__ . '/../..' . '/app/Attributes/CustomerHistory.php',
        'Disco\\App\\Attributes\\Product' => __DIR__ . '/../..' . '/app/Attributes/Product.php',
        'Disco\\App\\Attributes\\ProductHistory' => __DIR__ . '/../..' . '/app/Attributes/ProductHistory.php',
        'Disco\\App\\Campaign' => __DIR__ . '/../..' . '/app/Campaign.php',
        'Disco\\App\\Disco' => __DIR__ . '/../..' . '/app/Disco.php',
        'Disco\\App\\Intents\\BulkIntent' => __DIR__ . '/../..' . '/app/Intents/BulkIntent.php',
        'Disco\\App\\Intents\\BundleIntent' => __DIR__ . '/../..' . '/app/Intents/BundleIntent.php',
        'Disco\\App\\Intents\\BuyXGetXIntent' => __DIR__ . '/../..' . '/app/Intents/BuyXGetXIntent.php',
        'Disco\\App\\Intents\\BuyXGetYIntent' => __DIR__ . '/../..' . '/app/Intents/BuyXGetYIntent.php',
        'Disco\\App\\Intents\\CartIntent' => __DIR__ . '/../..' . '/app/Intents/CartIntent.php',
        'Disco\\App\\Intents\\Intent' => __DIR__ . '/../..' . '/app/Intents/Intent.php',
        'Disco\\App\\Intents\\IntentFactory' => __DIR__ . '/../..' . '/app/Intents/IntentFactory.php',
        'Disco\\App\\Intents\\IntentHelper' => __DIR__ . '/../..' . '/app/Intents/IntentHelper.php',
        'Disco\\App\\Intents\\ProductIntent' => __DIR__ . '/../..' . '/app/Intents/ProductIntent.php',
        'Disco\\App\\Intents\\ShippingIntent' => __DIR__ . '/../..' . '/app/Intents/ShippingIntent.php',
        'Disco\\App\\Utility\\Cache' => __DIR__ . '/../..' . '/app/Utility/Cache.php',
        'Disco\\App\\Utility\\Config' => __DIR__ . '/../..' . '/app/Utility/Config.php',
        'Disco\\App\\Utility\\DropDown' => __DIR__ . '/../..' . '/app/Utility/DropDown.php',
        'Disco\\App\\Utility\\Export' => __DIR__ . '/../..' . '/app/Utility/Export.php',
        'Disco\\App\\Utility\\Filter' => __DIR__ . '/../..' . '/app/Utility/Filter.php',
        'Disco\\App\\Utility\\Helper' => __DIR__ . '/../..' . '/app/Utility/Helper.php',
        'Disco\\App\\Utility\\Import' => __DIR__ . '/../..' . '/app/Utility/Import.php',
        'Disco\\App\\Utility\\Log' => __DIR__ . '/../..' . '/app/Utility/Log.php',
        'Disco\\App\\Utility\\Model' => __DIR__ . '/../..' . '/app/Utility/Model.php',
        'Disco\\App\\Utility\\Search' => __DIR__ . '/../..' . '/app/Utility/Search.php',
        'Disco\\App\\Utility\\Settings' => __DIR__ . '/../..' . '/app/Utility/Settings.php',
        'Disco\\Backend\\ActDeact' => __DIR__ . '/../..' . '/backend/ActDeact.php',
        'Disco\\Backend\\Enqueue' => __DIR__ . '/../..' . '/backend/Enqueue.php',
        'Disco\\Backend\\Settings_Page' => __DIR__ . '/../..' . '/backend/Settings_Page.php',
        'Disco\\Engine\\Base' => __DIR__ . '/../..' . '/engine/Base.php',
        'Disco\\Engine\\Context' => __DIR__ . '/../..' . '/engine/Context.php',
        'Disco\\Engine\\Initialize' => __DIR__ . '/../..' . '/engine/Initialize.php',
        'Disco\\Frontend\\Enqueue' => __DIR__ . '/../..' . '/frontend/Enqueue.php',
        'Disco\\Frontend\\Extras\\Body_Class' => __DIR__ . '/../..' . '/frontend/Extras/Body_Class.php',
        'Disco\\Rest\\Api' => __DIR__ . '/../..' . '/rest/Api.php',
        'Disco\\Rest\\CampaignApi' => __DIR__ . '/../..' . '/rest/CampaignApi.php',
        'Disco\\Rest\\DropDownApi' => __DIR__ . '/../..' . '/rest/DropDownApi.php',
        'Disco\\Rest\\SearchApi' => __DIR__ . '/../..' . '/rest/SearchApi.php',
        'Disco\\Rest\\SettingsApi' => __DIR__ . '/../..' . '/rest/SettingsApi.php',
        'Inpsyde\\Assets\\Asset' => __DIR__ . '/..' . '/inpsyde/assets/src/Asset.php',
        'Inpsyde\\Assets\\AssetFactory' => __DIR__ . '/..' . '/inpsyde/assets/src/AssetFactory.php',
        'Inpsyde\\Assets\\AssetManager' => __DIR__ . '/..' . '/inpsyde/assets/src/AssetManager.php',
        'Inpsyde\\Assets\\BaseAsset' => __DIR__ . '/..' . '/inpsyde/assets/src/BaseAsset.php',
        'Inpsyde\\Assets\\ConfigureAutodiscoverVersionTrait' => __DIR__ . '/..' . '/inpsyde/assets/src/ConfigureAutodiscoverVersionTrait.php',
        'Inpsyde\\Assets\\Exception\\FileNotFoundException' => __DIR__ . '/..' . '/inpsyde/assets/src/Exception/FileNotFoundException.php',
        'Inpsyde\\Assets\\Exception\\InvalidArgumentException' => __DIR__ . '/..' . '/inpsyde/assets/src/Exception/InvalidArgumentException.php',
        'Inpsyde\\Assets\\Exception\\InvalidResourceException' => __DIR__ . '/..' . '/inpsyde/assets/src/Exception/InvalidResourceException.php',
        'Inpsyde\\Assets\\Exception\\MissingArgumentException' => __DIR__ . '/..' . '/inpsyde/assets/src/Exception/MissingArgumentException.php',
        'Inpsyde\\Assets\\Handler\\AssetHandler' => __DIR__ . '/..' . '/inpsyde/assets/src/Handler/AssetHandler.php',
        'Inpsyde\\Assets\\Handler\\OutputFilterAwareAssetHandler' => __DIR__ . '/..' . '/inpsyde/assets/src/Handler/OutputFilterAwareAssetHandler.php',
        'Inpsyde\\Assets\\Handler\\OutputFilterAwareAssetHandlerTrait' => __DIR__ . '/..' . '/inpsyde/assets/src/Handler/OutputFilterAwareAssetHandlerTrait.php',
        'Inpsyde\\Assets\\Handler\\ScriptHandler' => __DIR__ . '/..' . '/inpsyde/assets/src/Handler/ScriptHandler.php',
        'Inpsyde\\Assets\\Handler\\StyleHandler' => __DIR__ . '/..' . '/inpsyde/assets/src/Handler/StyleHandler.php',
        'Inpsyde\\Assets\\Loader\\AbstractWebpackLoader' => __DIR__ . '/..' . '/inpsyde/assets/src/Loader/AbstractWebpackLoader.php',
        'Inpsyde\\Assets\\Loader\\ArrayLoader' => __DIR__ . '/..' . '/inpsyde/assets/src/Loader/ArrayLoader.php',
        'Inpsyde\\Assets\\Loader\\EncoreEntrypointsLoader' => __DIR__ . '/..' . '/inpsyde/assets/src/Loader/EncoreEntrypointsLoader.php',
        'Inpsyde\\Assets\\Loader\\LoaderInterface' => __DIR__ . '/..' . '/inpsyde/assets/src/Loader/LoaderInterface.php',
        'Inpsyde\\Assets\\Loader\\PhpFileLoader' => __DIR__ . '/..' . '/inpsyde/assets/src/Loader/PhpFileLoader.php',
        'Inpsyde\\Assets\\Loader\\WebpackManifestLoader' => __DIR__ . '/..' . '/inpsyde/assets/src/Loader/WebpackManifestLoader.php',
        'Inpsyde\\Assets\\OutputFilter\\AssetOutputFilter' => __DIR__ . '/..' . '/inpsyde/assets/src/OutputFilter/AssetOutputFilter.php',
        'Inpsyde\\Assets\\OutputFilter\\AsyncScriptOutputFilter' => __DIR__ . '/..' . '/inpsyde/assets/src/OutputFilter/AsyncScriptOutputFilter.php',
        'Inpsyde\\Assets\\OutputFilter\\AsyncStyleOutputFilter' => __DIR__ . '/..' . '/inpsyde/assets/src/OutputFilter/AsyncStyleOutputFilter.php',
        'Inpsyde\\Assets\\OutputFilter\\AttributesOutputFilter' => __DIR__ . '/..' . '/inpsyde/assets/src/OutputFilter/AttributesOutputFilter.php',
        'Inpsyde\\Assets\\OutputFilter\\DeferScriptOutputFilter' => __DIR__ . '/..' . '/inpsyde/assets/src/OutputFilter/DeferScriptOutputFilter.php',
        'Inpsyde\\Assets\\OutputFilter\\InlineAssetOutputFilter' => __DIR__ . '/..' . '/inpsyde/assets/src/OutputFilter/InlineAssetOutputFilter.php',
        'Inpsyde\\Assets\\Script' => __DIR__ . '/..' . '/inpsyde/assets/src/Script.php',
        'Inpsyde\\Assets\\Style' => __DIR__ . '/..' . '/inpsyde/assets/src/Style.php',
        'Inpsyde\\Assets\\Util\\AssetHookResolver' => __DIR__ . '/..' . '/inpsyde/assets/src/Util/AssetHookResolver.php',
        'Inpsyde\\Assets\\Util\\AssetPathResolver' => __DIR__ . '/..' . '/inpsyde/assets/src/Util/AssetPathResolver.php',
        'Inpsyde\\WpContext' => __DIR__ . '/..' . '/inpsyde/wp-context/src/WpContext.php',
        'Micropackage\\Internationalization\\Internationalization' => __DIR__ . '/..' . '/micropackage/internationalization/src/Internationalization.php',
        'Micropackage\\Requirements\\Abstracts\\Checker' => __DIR__ . '/..' . '/micropackage/requirements/src/Abstracts/Checker.php',
        'Micropackage\\Requirements\\Checker\\DocHooks' => __DIR__ . '/..' . '/micropackage/requirements/src/Checker/DocHooks.php',
        'Micropackage\\Requirements\\Checker\\PHP' => __DIR__ . '/..' . '/micropackage/requirements/src/Checker/PHP.php',
        'Micropackage\\Requirements\\Checker\\PHPExtensions' => __DIR__ . '/..' . '/micropackage/requirements/src/Checker/PHPExtensions.php',
        'Micropackage\\Requirements\\Checker\\Plugins' => __DIR__ . '/..' . '/micropackage/requirements/src/Checker/Plugins.php',
        'Micropackage\\Requirements\\Checker\\SSL' => __DIR__ . '/..' . '/micropackage/requirements/src/Checker/SSL.php',
        'Micropackage\\Requirements\\Checker\\Theme' => __DIR__ . '/..' . '/micropackage/requirements/src/Checker/Theme.php',
        'Micropackage\\Requirements\\Checker\\WP' => __DIR__ . '/..' . '/micropackage/requirements/src/Checker/WP.php',
        'Micropackage\\Requirements\\Interfaces\\Checkable' => __DIR__ . '/..' . '/micropackage/requirements/src/Interfaces/Checkable.php',
        'Micropackage\\Requirements\\Requirements' => __DIR__ . '/..' . '/micropackage/requirements/src/Requirements.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9fe709def937f5a07f9c3f81a821ac31::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9fe709def937f5a07f9c3f81a821ac31::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit9fe709def937f5a07f9c3f81a821ac31::$classMap;

        }, null, ClassLoader::class);
    }
}