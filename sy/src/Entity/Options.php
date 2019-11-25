<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Options
 *
 * @ORM\Table(name="options")
 * @ORM\Entity
 */
class Options
{
    /**
     * @var int
     *
     * @ORM\Column(name="OptionID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $optionid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="OptionGroupID", type="integer", nullable=true)
     */
    private $optiongroupid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="OptionName", type="string", length=50, nullable=true)
     */
    private $optionname;

    public function getOptionid(): ?int
    {
        return $this->optionid;
    }

    public function getOptiongroupid(): ?int
    {
        return $this->optiongroupid;
    }

    public function setOptiongroupid(?int $optiongroupid): self
    {
        $this->optiongroupid = $optiongroupid;

        return $this;
    }

    public function getOptionname(): ?string
    {
        return $this->optionname;
    }

    public function setOptionname(?string $optionname): self
    {
        $this->optionname = $optionname;

        return $this;
    }


}
