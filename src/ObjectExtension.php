<?php

namespace ilateral\SilverStripe\FeaturedImage;

use SilverStripe\Assets\Image;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\Hierarchy\Hierarchy;

class ObjectExtension extends DataExtension
{
    private static $has_one = [
        'FeaturedImage' => Image::class
    ];

    /**
     * Get a list of featured images from hierachy (if set). If not,
     * returns a list with the current image (if it exists).
     * 
     * This can be use to either generate a gallery, or to find the
     * most relevent image from hierachy by just getting the first image
     * in the list, eg:
     * 
     *      $FeaturedImagesFromHierachy.First
     * 
     * @return ArrayList 
     */
    public function getFeaturedImagesFromHierachy(): ArrayList
    {
        /** @var DataObject */
        $owner = $this->getOwner();
        $list = ArrayList::create();

        if (!$owner->hasExtension(Hierarchy::class)) {
            if ($owner->FeaturedImage()->exists()) {
                $list->add($owner->FeaturedImage());
            }

            return $list;
        }

        $ancestors = $owner->getAncestors(true);

        foreach ($ancestors as $ancestor) {
            if ($ancestor->FeaturedImage()->exists()) {
                $list->add($ancestor->FeaturedImage());
            }
        }

        return $list;
    }

    /**
     * Get a list of featured images from children, etc (if set). If not,
     * returns a list with the current image (if it exists).
     * 
     * This can be use to either generate a gallery, or to find the
     * most relevent image from descendants by just getting the first image
     * in the list, eg:
     * 
     *      $FeaturedImagesFromDescendants.First
     * 
     * @return ArrayList 
     */
    public function getFeaturedImagesFromDescendants(): DataList
    {
        /** @var DataObject */
        $owner = $this->getOwner();
        $owner_class = $owner->ClassName;
        $ids = [$owner->ID];

        if ($owner->hasExtension(Hierarchy::class)) {
            $ids = array_merge(
                $ids,
                $owner->getDescendantIDList()
            );
        }

        $image_ids = $owner_class::get()
            ->byIDs($ids)
            ->columnUnique('FeaturedImageID');

        // If no images, ensure we return an empty set
        if (count($image_ids) === 0) {
            $image_ids[] = 0;
        }

        return Image::get()->byIDs($image_ids);
    }
}
