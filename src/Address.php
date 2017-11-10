<?php 
namespace Zenodorus\Location;

use \Zenodorus\ZenodorusArguments as Zargs;

class Address
{
    /**
     * Mostly a wrapper for a ZendodorusArguments call, this also
     * sets up the properties we can expect to see for an address.
     *
     * @param array $args
     * @return ZenodorusArguments
     */
    public function rawAddress(array $args)
    {
        $Args = new Zargs(
            $args,
            [
                'street'    => false,
                'street2'   => false,
                'city'      => false,
                'state'     => false,
                'zip'       => false,
                'country'   => false,
            ]
        );

        return $Args;
    }

    /**
     * Return a nice URL to this address.
     *
     * @param string $url_fragment
     * @param array $args
     * @return string
     */
    public static function addressUrl(string $url_fragment, array $args)
    {
        $Address = new static;
        return sprtinf(
            "%s%s", 
            $url_fragment, 
            \urlencode(
                join(
                    ',',
                    \array_diff(
                        $Address->rawAddress($args)->getAll(),
                        [false]
                    )
                )
            )
        );
    }

    /**
     * Return a nice URL to this address on Google Maps.
     *
     * @param array $args
     * @return string
     */
    public static function addressUrlGmaps(array $args)
    {
        $Address = new static;
        return $Address->addressUrl('https://www.google.com/maps/dir/?api=1&destination=', $args);
    }

    /**
     * Get an array containing the existing address parts
     * and return an array with those assigned to keys for
     * the correct schema properties.
     *
     * @param array $args
     * @return array
     */
    public static function addressSchema(array $args)
    {
        $Location = new static;
        $Address = $Location->rawAddress($args);

        $schema_map = [
            'street' => 'streetAddress',
            'street2' => 'streetAddress',
            'zip' => 'postalCode',
            'city' => 'addressLocality',
            'state' => 'addressRegion',
            'country' => 'addressCountry',
        ];
        $final_schema = [];
        foreach ($schema_map as $field => $schema_field) {
            if ($Address->get($field)) {
                if (isset($final_schema[$schema_field])) {
                    $final_schema[$schema_field] = sprintf(
                        "%s, %s", 
                        $final_schema[$schema_field], 
                        $Address->get($field)
                    );
                } else {
                    $final_schema[$schema_field] = $Address->get($field);
                }
            }
        }

        return $final_schema;
    }

    /**
     * Get an HTML element with all the proper PostalAddress
     * schema tags.
     *
     * @param array $args
     * @return string
     */
    public static function addressSchemaElement(array $args)
    {
        $Location = new static;
        $schema = $Location->addressSchema($args);
        $compiled_schema = PHP_EOL;
        foreach ($schema as $type => $value) {
            $compiled_schema .= sprintf(
                '<span itemprop="%s">%s</span>%s',
                $type,
                $value,
                PHP_EOL
            );
        }
        return sprintf(
            '<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">%s</div>',
            $compiled_schema
        );
    }

    /**
     * Get a JSON-encoded string containing the address
     * in proper JSON-LD format.
     *
     * @param array $args
     * @return string
     */
    public static function addressSchemaJSON(array $args)
    {
        $Location = new static;
        $schema = $Location->addressSchema($args);
        $schema['@type'] = 'PostalAddress';
        return \json_encode($schema);
    }
}
