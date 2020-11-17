<?php


namespace App\Repository;


class RealStateRepository extends AbstractRepository
{
    private $location;

    public function setLocation(array $data): self
    {
        $this->location = $data;
        return $this;
    }

    public function getResult()
    {
        $location = $this->location;

        return $this->model->whereHas('address', function ($q) use ($location){
                    if ($location['state'])
                        $q->where('state_id', $location['state']);

                    if ($location['city'])
                        $q->where('city_id', $location['city']);

                    if ($location['address'])
                        $q->where('address', 'like', '%'.$location['address'].'%');
                });
    }
}
