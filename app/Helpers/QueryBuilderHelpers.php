<?php

if(!function_exists('sqlsrvAddColumDesc'))
{
    /**
     * Add table column descriptions in sqlsrv
     * 
     * @param array $request
     * @param array $filterables (where ['request attribute name' => 'query column name'] as pair)
     * @return array
     */
    function sqlsrvAddTableColumDescs($schema, $table, $columns)
    {
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");

        if ($driver == 'sqlsrv') {
            foreach ($columns as $column) {
                DB::unprepared("
                    EXEC sp_addextendedproperty 
                        @name = N'MS_Description', @value = '{$column['desc']}',
                        @level0type = N'Schema',   @level0name = '{$schema}',
                        @level1type = N'Table',    @level1name = '{$table}',
                        @level2type = N'Column',   @level2name = '{$column['name']}';
                    "
                );
            }
        }
    }
}