<?php


class thestudent
{
    protected $idthestudent, $thename, $thesurname;


    protected function hydrate(array $values){
        foreach($values AS $key => $value){
            $setterName = "set".ucfirst($key);
            if(method_exists($this, $setterName)){
                $this->$setterName($value);
            }
        }
    }

    public function __construct(array $donnees = [])
    {
        if(empty($donnees)){
            $this->setThename(" Yepâ <br>");
        }else{
            $this->hydrate($donnees);
        }
    }

    public function getIdthestudent(): int
    {
        return $this->idthestudent;
    }

    public function getThename(): string
    {
        return $this->thename;
    }

    public function getThesurname(): string
    {
        return $this->thesurname;
    }

    public function setIdthestudent(int $idthestudent): void
    {
        if(!empty($idthestudent)) {
            $this->idthestudent = $idthestudent;
        }
    }


    public function setThename(string $thename): void
    {
        $this->thename = htmlspecialchars(strip_tags(trim($thename)),ENT_QUOTES);
    }

    public function setThesurname(string $thesurname): void
    {
        $this->thesurname = htmlspecialchars(strip_tags(trim($thesurname)),ENT_QUOTES);
    }


}