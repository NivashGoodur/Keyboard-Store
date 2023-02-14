<?php
namespace App\EventSubscriber;

use App\Entity\Product;
use Cloudinary\Tag\ImageTag;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $appKernel;

    public function __construct(KernelInterface $appKernel) 
    {
        $this->appKernel = $appKernel;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setIllustration'],
            BeforeEntityUpdatedEvent::class => ['updateIllustration']
        ];
    }

    public function uploadIllustration($event)
    {
        $entity = $event->getEntityInstance();
       
        $config = Configuration::instance();
        $config->cloud->cloudName = 'dmxwxl8n7';
        $config->cloud->apiKey = '747859687579744';
        $config->cloud->apiSecret = '3ViTYuh4PBTjkTf2po1faxXGsk8';
        $config->url->secure = true;


        $imgName = $_FILES['Product']['name']['illustration']['file'];
        $project_dir = $this->appKernel->getProjectDir();


        $upload = new UploadApi();
        $upload->upload($project_dir.'/public/uploads/'.$imgName, ['public_id' => $imgName]);
        $imgtag = (string)(new ImageTag($imgName.'.webp'));

        //take off balise img
        preg_match( '@src="([^"]+)"@' , $imgtag, $match );
        $srcImage = array_pop($match);

        //delete in upload folder bcs its uploaded to cloudinary
        unlink($project_dir.'/public/uploads/'.$imgName);

        $entity->setIllustration($srcImage);
    }

    public function updateIllustration(BeforeEntityUpdatedEvent $event)
    {
        
        if (!($event->getEntityInstance() instanceof Product)) {
            return;
        }


        if ($_FILES['Product']['tmp_name']['illustration'] != '') {
            $this->uploadIllustration($event);
        }

    }

    public function setIllustration(BeforeEntityPersistedEvent $event)
    {
        if (!($event->getEntityInstance() instanceof Product)) {
            return;
        }


        $this->uploadIllustration($event);
    }
}