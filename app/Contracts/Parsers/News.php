<?php

namespace App\Contracts\Parsers;

interface News
{

    /** Array of xPaths for dom elements selectors
     * @return array
     */
    public function xPaths(): array;


    /** Parse preview of news and pre-fill model
     * @param int $count
     * @return array
     */
    public function parseTopNews(int $count = 15): array;


    /** Parse fool news (by link from preview parsed)
     * @param $item
     * @return array
     */
    public function parseSingleNews($item): array;


    /** get all fool news from previews by parseTopNews()
     * @return array
     */
    public function parseListNews(): array;


    /** parse DOM by xPaths-patterns
     * @param \DOMXpath $xpath
     * @param string $variant
     * @return array
     */
    public function getElements(\DOMXpath $xpath, string $variant = 'default'): array;


    /** data to view single fool news
     * @param $publish_date
     * @return mixed
     */
    public function news($publish_date);


    /** url for initial parse (list of previews)
     * @return mixed
     */
    public function currentUrl();


    /** data for view (previews list)
     * @return mixed
     */
    public function index();

}
