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
}