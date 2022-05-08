<?php

namespace ilateral\SilverStripe\FeaturedImage;

use SilverStripe\ORM\DB;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\Queries\SQLSelect;
use SilverStripe\ORM\Queries\SQLUpdate;

class BlogFeaturedImageMigrationTask extends DataExtension
{
    /**
     * Quickly tap into dev/build process to copy featured image IDs
     * to global sitetree table
     */
    public function requireDefaultRecords()
    {
        $image_ids = SQLSelect::create()
            ->setFrom('BlogPost')
            ->selectField('ID')
            ->selectField('FeaturedImageID')
            ->execute();

        $migrated = 0;
        $count = count((array)$image_ids);

        DB::alteration_message("- Copying Featured Images from {$count} Blog Posts");

        foreach ($image_ids as $row) {
            SQLUpdate::create('SiteTree')
                ->addWhere(['ID' => $row['ID']])
                ->assign('"FeaturedImageID"', $row['FeaturedImageID'])
                ->execute();

            $migrated++; 
        }

        DB::alteration_message("- Copied {$migrated} images");
    }
}
