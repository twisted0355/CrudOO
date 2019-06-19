<?php


class theuser
{
    // attributs
    protected $idtheuser;
    protected $thelogin;
    protected $thepwd;

    // méthodes

        // constructeur
    public function __construct(array $datas = [])
    {
        // tableau vide
        if(empty($datas)){

            // login anonyme
            $this->thelogin = "Anonyme";
        }else{
            // hydratation
            $this->hydrate($datas);
        }
    }

    // fonction d'hydratation , sert à utiliser les $datas du constructeur pour appeler les setters et remplir les attributs

    protected function hydrate(array $values){
        foreach($values AS $key => $value){
            $setterName = "set".ucfirst($key);
            if(method_exists($this, $setterName)){
                $this->$setterName($value);
            }
        }
    }

    // getters

    public function getIdtheuser(){
        return $this->idtheuser;
    }
    public function getThelogin(){
        return html_entity_decode($this->thelogin,ENT_QUOTES);
    }
    public function getThepwd(){
        return $this->thepwd;
    }



    // setters

    public function setIdtheuser(int $idtheuser): void
    {
        if(!empty($idtheuser)) {
            $this->idtheuser = $idtheuser;
        }
    }
    public function setThelogin(string $thelogin): void
    {
        $this->thelogin = htmlspecialchars(strip_tags(trim($thelogin)),ENT_QUOTES);
    }
    public function setThepwd(string $thepwd): void
    {
        $this->thepwd = $this->sha256(trim($thepwd));
    }

    // methodes protected qui crypte en sha256

    public function sha256(string $arg){
        return hash("sha256",$arg);
    }

}