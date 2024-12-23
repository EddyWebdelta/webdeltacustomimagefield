<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class WebdeltaCustomImageField extends Module
{
    public function __construct()
    {
        $this->name = 'webdeltacustomimagefield';
        $this->version = '1.0.0';
        $this->author = 'Eddy | Webdelta';
        $this->tab = 'administration';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans('Extra Image for CMS Pages');
        $this->description = $this->trans('Adds an extra image field to CMS pages');
    }

    public function install()
    {
        return parent::install() &&
            $this->installDb() &&
            $this->registerHook('actionAfterUpdateCmsFormHandler');
    }

    public function installDb()
    {
        $sql = "SHOW COLUMNS FROM "._DB_PREFIX_."cms LIKE 'custom_image'";
        $result = Db::getInstance()->executeS($sql);

        if (empty($result)) {
            return Db::getInstance()->execute('
                ALTER TABLE '._DB_PREFIX_.'cms ADD `custom_image` VARCHAR(255) NULL
            ');
        }
        return true;
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->uninstallDb();
    }

    public function uninstallDb()
    {
        return Db::getInstance()->execute('
            ALTER TABLE '._DB_PREFIX_.'cms DROP `custom_image`
        ');
    }

    public function hookActionAfterUpdateCmsFormHandler(array $params)
    {
        if (isset($_FILES['cms']['name']['custom_image']) && !empty($_FILES['cms']['name']['custom_image'])) {
            $file = $_FILES['cms'];
            $cmsId = $params['id'];

            $targetDir = _PS_IMG_DIR_ . 'cms/';
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $targetFile = $targetDir . basename($file['name']['custom_image']);

            if (move_uploaded_file($file['tmp_name']['custom_image'], $targetFile)) {
                Db::getInstance()->update('cms', [
                    'custom_image' => '/img/cms/' . basename($file['name']['custom_image'])
                ], 'id_cms = '.(int)$cmsId);
            }
        }
    }
}