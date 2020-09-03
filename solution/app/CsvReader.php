<?php


namespace App;


class CsvReader {
    const HEADER = ["Label", "Value", "Date"];
    const MAX_LINE = 512;
    const CSV_DELIMITER = ',';

    /**
     * @var string
     */
    private $file_fullpath;
    /**
     * @var int
     */
    private $batch_size;

    /**
     * CsvReader constructor.
     * @param string $file_fullpath
     * @param int $batch_size
     */
    public function __construct(string $file_fullpath, int $batch_size)
    {
        $this->file_fullpath = $file_fullpath;
        $this->batch_size = $batch_size;
    }

    public function batches()
    {
        if (($handle = fopen($this->file_fullpath, "r")) === false) {
            return;
        }

        $row = 0;
        $batch = [];

        // Header check is extracted outside the loop to avoid doing the check with every item.
        if (($item = fgetcsv($handle, self::MAX_LINE, self::CSV_DELIMITER)) !== false) {
            if ($item !== self::HEADER) {
                $batch[] = $item;
                // Check in case batch size is 1
                if (++$row % $this->batch_size === 0) {
                    yield $batch;
                    $batch = [];
                }
            }
        }

        while (($item = fgetcsv($handle, self::MAX_LINE, self::CSV_DELIMITER)) !== false) {
            $batch[] = $item;
            if (++$row % $this->batch_size === 0) {
                yield $batch;
                $batch = [];
            }
        }
        fclose($handle);

        if ([] !== $batch) {
            // Remaining batch with few items
            yield $batch;
        }

    }

    function recordCount()
    {
        // Courtesy https://stackoverflow.com/questions/2162497/efficiently-counting-the-number-of-lines-of-a-text-file-200mb
        $lines = 0;
        if (($handle = fopen($this->file_fullpath, "rb")) === false) {
            return $lines;
        }

        while (!feof($handle)) {
            $lines += substr_count(fread($handle, 8192), "\n");
        }

        fclose($handle);

        return $lines;
    }

    private function valid(array $item)
    {
        return true;
    }
}
