<?php

namespace App\Imports;

use App\Models\Purchase;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PurchasesImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        /*
        |--------------------------------------------------------------------------
        | 1. FIND HEADER ROW AUTOMATICALLY
        |--------------------------------------------------------------------------
        */

        $headerIndex = null;
        $headings = [];

        foreach ($rows as $index => $row) {

            $possibleHeadings = [];

            foreach ($row as $key => $value) {

                $heading = strtolower(
                    trim((string) $value)
                );

                $heading = str_replace(
                    [' ', '-'],
                    '_',
                    $heading
                );

                $possibleHeadings[$key] = $heading;
            }

            /*
            |--------------------------------------------------------------------------
            | Check if this row contains our important columns
            |--------------------------------------------------------------------------
            */

            $hasBuyingDate = in_array(
                'buying_date',
                $possibleHeadings
            );

            $hasVin = in_array(
                'vin',
                $possibleHeadings
            );

            $hasUser = in_array(
                'user',
                $possibleHeadings
            );

            if (
                $hasBuyingDate &&
                $hasVin
            ) {

                $headerIndex = $index;
                $headings = $possibleHeadings;

                break;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | 2. HEADER NOT FOUND
        |--------------------------------------------------------------------------
        */

        if ($headerIndex === null) {

            throw new \Exception(
                'Excel headings not found. Please make sure your Excel file contains: user, buying_date, vin.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 3. IMPORT DATA AFTER HEADER
        |--------------------------------------------------------------------------
        */

        foreach ($rows as $index => $row) {

            /*
            |--------------------------------------------------------------------------
            | Skip heading and previous rows
            |--------------------------------------------------------------------------
            */

            if ($index <= $headerIndex) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Convert row to associative array
            |--------------------------------------------------------------------------
            */

            $data = [];

            foreach ($headings as $key => $heading) {

                $data[$heading] = $row[$key] ?? null;
            }


            /*
            |--------------------------------------------------------------------------
            | Check if row is completely empty
            |--------------------------------------------------------------------------
            */

            $hasData = false;

            foreach ($data as $value) {

                if (
                    $value !== null &&
                    trim((string) $value) !== ''
                ) {

                    $hasData = true;

                    break;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Skip blank row
            |--------------------------------------------------------------------------
            */

            if (!$hasData) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | USER NAME
            |--------------------------------------------------------------------------
            */

            $userName = trim(
                (string) ($data['user'] ?? '')
            );


            /*
            |--------------------------------------------------------------------------
            | VIN
            |--------------------------------------------------------------------------
            */

            $vin = trim(
                (string) ($data['vin'] ?? '')
            );


            /*
            |--------------------------------------------------------------------------
            | BUYING DATE
            |--------------------------------------------------------------------------
            */

            $rawBuyingDate = $data['buying_date'] ?? null;

            $buyingDate = $this->convertExcelDate(
                $rawBuyingDate
            );


            /*
            |--------------------------------------------------------------------------
            | CHECK BUYING DATE
            |--------------------------------------------------------------------------
            */

            if (!$buyingDate) {

                throw new \Exception(
                    'Buying Date is missing or invalid on Excel row ' .
                    ($index + 1) .
                    '. VIN: ' .
                    ($vin ?: 'Unknown')
                );
            }


            /*
            |--------------------------------------------------------------------------
            | FIND USER
            |--------------------------------------------------------------------------
            */

            $user = null;

            if ($userName !== '') {

                $user = User::where(
                    'name',
                    $userName
                )->first();
            }


            /*
            |--------------------------------------------------------------------------
            | IF USER DOES NOT EXIST
            | USE DEMO USER
            |--------------------------------------------------------------------------
            */

            if ($user) {

                $userId = $user->id;

            } else {

                $demoUser = User::where(
                    'name',
                    'Demo'
                )->first();

                if (!$demoUser) {

                    throw new \Exception(
                        "User '{$userName}' was not found and Demo user does not exist."
                    );
                }

                $userId = $demoUser->id;
            }


            /*
            |--------------------------------------------------------------------------
            | DATE OF ARRIVING
            |--------------------------------------------------------------------------
            */

            $dateOfArriving = $this->convertExcelDate(
                $data['date_of_arriving'] ?? null
            );


            /*
            |--------------------------------------------------------------------------
            | CREATE PURCHASE
            |--------------------------------------------------------------------------
            */

            Purchase::create([

                /*
                |--------------------------------------------------------------------------
                | USER
                |--------------------------------------------------------------------------
                */

                'user_id' => $userId,


                /*
                |--------------------------------------------------------------------------
                | VEHICLE INFORMATION
                |--------------------------------------------------------------------------
                */

                'buying_date' => $buyingDate,

                'lot_number' => $this->value(
                    $data['lot_number'] ?? null
                ),

                'vin' => $this->value(
                    $data['vin'] ?? null
                ),

                'cylinder' => $this->value(
                    $data['cylinder'] ?? null
                ),

                'color' => $this->value(
                    $data['color'] ?? null
                ),

                'make' => $this->value(
                    $data['make'] ?? null
                ),

                'model' => $this->value(
                    $data['model'] ?? null
                ),


                /*
                |--------------------------------------------------------------------------
                | PURCHASE COSTS
                |--------------------------------------------------------------------------
                */

                'buying_fee' => $this->number(
                    $data['buying_fee'] ?? 0
                ),

                'towing_fee' => $this->number(
                    $data['towing_fee'] ?? 0
                ),

                'shipping' => $this->number(
                    $data['shipping'] ?? 0
                ),


                /*
                |--------------------------------------------------------------------------
                | CALCULATED COSTS
                |--------------------------------------------------------------------------
                */

                'total_aed' => $this->number(
                    $data['total_aed'] ?? 0
                ),

                'clearing' => $this->number(
                    $data['clearing'] ?? 0
                ),

                'extra_charges' => $this->number(
                    $data['extra_charges'] ?? 0
                ),

                'custom_duty' => $this->number(
                    $data['custom_duty'] ?? 0
                ),

                'grand_total' => $this->number(
                    $data['grand_total'] ?? 0
                ),


                /*
                |--------------------------------------------------------------------------
                | SALE INFORMATION
                |--------------------------------------------------------------------------
                */

                'selling_price' => $this->number(
                    $data['selling_price'] ?? 0
                ),

                'profit' => $this->number(
                    $data['profit'] ?? 0
                ),


                /*
                |--------------------------------------------------------------------------
                | OTHER INFORMATION
                |--------------------------------------------------------------------------
                */

                'bill_no' => $this->value(
                    $data['bill_no'] ?? null
                ),

                'date_of_arriving' => $dateOfArriving,

                'location' => $this->value(
                    $data['location'] ?? null
                ),

                'customer_name' => $this->value(
                    $data['customer_name'] ?? null
                ),


                /*
                |--------------------------------------------------------------------------
                | STATUS
                |--------------------------------------------------------------------------
                */

                'status' => $this->value(
                    $data['status'] ?? null
                ) ?: 'Purchased',


                /*
                |--------------------------------------------------------------------------
                | DESCRIPTION
                |--------------------------------------------------------------------------
                */

                'description' => $this->value(
                    $data['description'] ?? null
                ),
            ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CONVERT EXCEL DATE
    |--------------------------------------------------------------------------
    */

    private function convertExcelDate($value = null)
    {
        /*
        |--------------------------------------------------------------------------
        | Empty
        |--------------------------------------------------------------------------
        */

        if ($value === null || $value === '') {
            return null;
        }


        /*
        |--------------------------------------------------------------------------
        | Excel DateTime
        |--------------------------------------------------------------------------
        */

        if ($value instanceof \DateTimeInterface) {

            return Carbon::instance($value)
                ->format('Y-m-d');
        }


        /*
        |--------------------------------------------------------------------------
        | Excel Serial Date
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | 46302
        |
        */

        if (is_numeric($value)) {

            try {

                return Date::excelToDateTimeObject(
                    (float) $value
                )->format('Y-m-d');

            } catch (\Throwable $e) {

                return null;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Date String
        |--------------------------------------------------------------------------
        */

        try {

            return Carbon::parse(
                trim((string) $value)
            )->format('Y-m-d');

        } catch (\Throwable $e) {

            return null;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | NUMBER
    |--------------------------------------------------------------------------
    */

    private function number($value = null)
    {
        if ($value === null || $value === '') {
            return 0;
        }

        if (is_string($value)) {

            $value = str_replace(
                ',',
                '',
                $value
            );

            $value = trim($value);
        }

        if (is_numeric($value)) {

            return (float) $value;
        }

        return 0;
    }


    /*
    |--------------------------------------------------------------------------
    | TEXT VALUE
    |--------------------------------------------------------------------------
    */

    private function value($value = null)
    {
        if ($value === null) {
            return null;
        }

        $value = trim(
            (string) $value
        );

        if ($value === '') {
            return null;
        }

        return $value;
    }
}