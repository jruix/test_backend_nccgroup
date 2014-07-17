<?php
include "ArrayParser.php";
$inputData = Array(
    'name' => 'account',
    'attr' => Array(
        'id' => '123456'
    ),
    'children' => Array(
        Array(
            'name' => 'name',
            'attr' => Array(),
            'children' => Array(
                'BBC'
            ),
        ),
        Array(
            'name' => 'monitors',
            'attr' => Array(),
            'children' => Array(
                Array(
                    'name' => 'monitor',
                    'attr' => Array(
                        'id' => '5235632'
                    ),
                    'children' => Array(
                        Array(
                            'name' => 'url',
                            'attr' => Array(),
                            'children' => Array(
                                'http://www.bbc.co.uk/'
                            )
                        )
                    )
                ),
                Array(
                    'name' => 'monitor',
                    'attr' => Array(
                        'id' => '5235633'
                    ),
                    'children' => Array(
                        Array(
                            'name' => 'url',
                            'attr' => Array(),
                            'children' => Array(
                                'http://www.bbc.co.uk/news'
                            )
                        )
                    )
                )
            )
        )
    )
);

$arrayParser = new ArrayParser($inputData);
echo $arrayParser->getXmlDocument();
