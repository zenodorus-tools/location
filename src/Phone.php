<?php 
namespace Zenodorus\Location;

use \Zenodorus\ZenodorusArguments as Zargs;

class Phone
{
    /**
     * Mostly a wrapper for a ZendodorusArguments call, this also
     * sets up the properties we can expect to see for an phone number.
     *
     * @param array $args
     * @return ZenodorusArguments
     */
    public function rawPhone(array $args)
    {
        $Args = new Zargs(
            $args,
            [
                'num'       => [
                    1,          // country code
                    false,      // area code
                    false,      // first set
                    false,      // second set
                    '',         // extenstion
                ],
                'separator' => '-',
                'xprefix'   => 'x'
            ]
        );
        return $Args;
    }

    /**
     * Get an HTML element with all the proper telephone
     * schema tags.
     *
     * @param array $args
     * @return void
     */
    public static function phoneSchemaElement(array $args)
    {
        $Phone = new static;
        $numbers = $Phone->rawPhone($args)->get('num');
        $separator = $Phone->rawPhone($args)->get('separator');
        return sprintf(
            '<span itemprop="telephone" content="+%s">%s</span>',
            join('', \array_diff($numbers, [false, ''])),
            join($separator, $numbers)
        );
    }

    /**
     * Get a JSON-encoded string containing the telephone
     * in proper JSON-LD format.
     *
     * @param array $args
     * @return void
     */
    public static function phoneSchemaJSON(array $args)
    {
        return \json_encode([
            'telephone' => '+' . join(
                '', 
                \array_diff($Phone->rawPhone($args)->get('num'), [false, ''])
            )
        ]);
    }
}