<?php

/**
 * ClassFinder Tool for PHP OOP
 *
 * @since May 2014
 * @author Yalçın CEYLAN <creator@yalcinceylan.net>
 * @link http://yalcinceylan.net
 * @license MIT <http://opensource.org/licenses/mit-license.php>
 */
class ClassFinder {

    /**
     * Dosya yolunu tutar.
     *
     * @var string
     */
    protected $location;

    /**
     * Dosya kaynağını tutar.
     *
     * @var string
     */
    protected $source;

    /**
     * Sınıfları tutar.
     *
     * @var array
     */
    protected $classes = array();

    /**
     * Bir kaç tanımlama yapılır.
     *
     * @param $location
     * @return mixed
     */
    public function __construct($location)
    {

        if ( is_readable($location) )
        {

            $this->location = $location;

            $this->source = file_get_contents($location);

            $this->process();

        }

    }

    /**
     * Kaynak dosyayı tarar ve bulduğu sınıfları tanımlar.
     *
     * @return void
     */
    private function process()
    {

        preg_match_all('@^(class|abstract\s+class|final\s+class)\s+([A-Za-z_]+)\s?(extends\s+[A-Za-z_]+)?\s?(implements\s+.*?)?\s?{(.*?)}@sm', $this->source, $results, PREG_SET_ORDER);

        foreach($results as $result)
        {

            list($complete, $style, $class, $extends, $implements, $content) = $result;

            $params = array('abstract' => false, 'final' => false, 'extended' => null, 'implemented' => array());

            if ( strpos($style, 'abstract') !== false ) $params['abstract'] = true;

            if ( strpos($style, 'final') !== false ) $params['final'] = true;

            if ( $extends ) $params['extended'] = preg_replace('/\s/', '', str_replace('extends', '', $extends));

            if ( $implements ) $params['implemented'] = explode(',', preg_replace('/\s/', '', str_replace('implements', '', $implements)));

            $this->classes[$class] = $params;

        }

    }

    /**
     * Bulunan tüm sınıfları dizi şeklinde döndürür.
     *
     * @return array
     */
    public function getClasses()
    {

        return $this->classes;

    }

    /**
     * Bulunan sınıfların sayısını döndürür.
     *
     * @return int
     */
    public function getClassCount()
    {

        return count($this->classes);

    }

    /**
     * Belirtilen sınıfın var olup olmadığını doğrular.
     *
     * @param $className
     * @return bool
     */
    public function hasClass($className)
    {

        return isset($this->classes[$className]);

    }

    /**
     * Belirtilen sınıf varsa getirir, yoksa boş döner.
     *
     * @param $className
     * @return null
     */
    public function getClass($className)
    {

        if ( !$this->hasClass($className) ) return null;

        return $this->classes[$className];

    }

    /**
     * Belirtilen normal sınıfın var olup olmadığını doğrular.
     *
     * @param $className
     * @return bool
     */
    public function hasNormalClass($className)
    {

        if ( !$this->hasClass($className) ) return false;

        $class = $this->getClass($className);

        if ( $class['abstract'] === false && $class['final'] === false ) return true;

        return false;

    }

    /**
     * Belirtilen son sınıfın var olup olmadığını doğrular.
     *
     * @param $className
     * @return bool
     */
    public function hasFinalClass($className)
    {

        if ( !$this->hasClass($className) ) return false;

        $class = $this->getClass($className);

        if ( $class['final'] === true ) return true;

        return false;

    }

    /**
     * Belirtilen soyut sınıfın var olup olmadığını doğrular.
     *
     * @param $className
     * @return bool
     */
    public function hasAbstractClass($className)
    {

        if ( !$this->hasClass($className) ) return false;

        $class = $this->getClass($className);

        if ( $class['abstract'] === true ) return true;

        return false;

    }

}