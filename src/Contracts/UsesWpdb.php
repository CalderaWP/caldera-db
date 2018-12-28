<?php


namespace calderawp\CalderaForms\Forms\Contracts;

interface UsesWpdb
{

    /**
     * Set a new instance of WPDB.
     *
     * @param  \WPDB $wpdb
     * @return $this
     */
    public function setWpdb(\WPDB $wpdb);

    /**
     * Get current instance of WPDB
     *
     * @return \WPDB
     */
    public function getWpdb();

    /**
     * Sql to query, using
     *
     * wpdb::getResults($sql)
     *
     * @param  string $sql The sql query to get results for
     * @return \stdClass[]
     */
    public function getResults($sql);
}
