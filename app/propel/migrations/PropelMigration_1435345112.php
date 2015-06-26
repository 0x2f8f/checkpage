<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1435345112.
 * Generated on 2015-06-26 23:58:32 by id0x2f8f
 */
class PropelMigration_1435345112
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

ALTER TABLE `project` DROP PRIMARY KEY;

ALTER TABLE `project` ADD PRIMARY KEY (`id`,`user_id`);

ALTER TABLE `project` ADD CONSTRAINT `project_FK_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `fos_user` (`id`)
    ON UPDATE SET NULL
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

ALTER TABLE `project` DROP PRIMARY KEY;

ALTER TABLE `project` DROP FOREIGN KEY `project_FK_1`;

ALTER TABLE `project` ADD PRIMARY KEY (`id`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}