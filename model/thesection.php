<?php


class thesection
{
    protected $idthesection;
    protected $thetitle;
    protected $thedesc;

    /**
     * thesection constructor.
     */
    public function __construct(array $datas=[])
    {
        if(empty($datas)){
            $this->setThetitle("Sans titre");
        }else{
            $this->hydrate($datas);
        }
    }

    protected function hydrate(array $values){
        foreach($values AS $key => $value){
            $setterName = "set".ucfirst($key);
            if(method_exists($this, $setterName)){
                $this->$setterName($value);
            }
        }
    }

    public function getIdthesection(): int
    {
        return $this->idthesection;
    }

    public function setIdthesection(int $idthesection): void
    {
        if(!empty($idthesection)) {
            $this->idthesection = $idthesection;
        }
    }

    public function getThetitle()
    {
        if(empty($this->thetitle)){
            return NULL;
        }else{
            return $this->thetitle;
        }

    }

    public function setThetitle(string $thetitle): void
    {
        $this->thetitle = htmlspecialchars(strip_tags(trim($thetitle)),ENT_QUOTES);
    }

    public function getThedesc(): string
    {
        return $this->thedesc;
    }

    public function setThedesc(string $thedesc): void
    {
        $this->thedesc = htmlspecialchars(strip_tags(trim($thedesc),"<p><br>"),ENT_QUOTES);
    }


}