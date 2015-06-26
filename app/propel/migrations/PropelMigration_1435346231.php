<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1435346231.
 * Generated on 2015-06-27 00:17:11 by id0x2f8f
 */
class PropelMigration_1435346231
{

    public function preUp($manager)
    {
        // add the pre-migration code here
    }

    public function postUp($manager)
    {
        // add the post-migration code here
    }

    public function preDown($manager)
    {
        // add the pre-migration code here
    }

    public function postDown($manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `project` CHANGE `user_id` `user_id` INTEGER;

CREATE INDEX `project_FI_1` ON `project` (`user_id`);

ALTER TABLE `project` ADD CONSTRAINT `project_FK_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `fos_user` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `project` DROP FOREIGN KEY `project_FK_1`;

DROP INDEX `project_FI_1` ON `project`;

ALTER TABLE `project` CHANGE `user_id` `user_id` INTEGER NOT NULL;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}