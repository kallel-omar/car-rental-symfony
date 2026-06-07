<?php

namespace App\DataFixtures;

use App\Entity\Car;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CarFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $cars = [
            ['Toyota', 'Corolla', 120, 'toyota-corolla.jpg', 'TU-1001', 2021, 'Automatic', 'Petrol', 5, 'Comfortable and reliable sedan.', 'available'],
            ['Volkswagen', 'Golf', 150, 'volkswagen-golf.jpg', 'TU-1002', 2020, 'Manual', 'Diesel', 5, 'Compact car suitable for city and highway driving.', 'available'],
            ['BMW', 'Series 3', 280, 'bmw-series-3.jpg', 'TU-1003', 2022, 'Automatic', 'Diesel', 5, 'Premium sedan with excellent comfort.', 'available'],
            ['Mercedes-Benz', 'C-Class', 300, 'mercedes-c-class.jpg', 'TU-1004', 2022, 'Automatic', 'Petrol', 5, 'Luxury vehicle suitable for business trips.', 'available'],
            ['Audi', 'A4', 260, 'audi-a4.jpg', 'TU-1005', 2021, 'Automatic', 'Petrol', 5, 'Elegant and powerful German sedan.', 'available'],
            ['Renault', 'Clio', 90, 'renault-clio.jpg', 'TU-1006', 2019, 'Manual', 'Petrol', 5, 'Economical car ideal for daily use.', 'available'],
            ['Hyundai', 'i20', 100, 'hyundai-i20.jpg', 'TU-1007', 2020, 'Manual', 'Petrol', 5, 'Small and practical city car.', 'available'],
            ['Kia', 'Sportage', 220, 'kia-sportage.jpg', 'TU-1008', 2021, 'Automatic', 'Diesel', 5, 'Spacious SUV suitable for family trips.', 'available'],
            ['Peugeot', '208', 95, 'peugeot-208.jpg', 'TU-1009', 2020, 'Manual', 'Petrol', 5, 'Modern compact car with low fuel consumption.', 'available'],
            ['Dacia', 'Duster', 180, 'dacia-duster.jpg', 'TU-1010', 2021, 'Manual', 'Diesel', 5, 'Practical SUV suitable for different roads.', 'available'],
        ];

        foreach ($cars as [$brand, $model, $price, $image, $registration, $year, $transmission, $fuel, $seats, $description, $status]) {
            $car = new Car();

            $car->setBrand($brand);
            $car->setModel($model);
            $car->setPricePerDay($price);
            $car->setImage($image);
            $car->setRegistrationNumber($registration);
            $car->setYear($year);
            $car->setTransmission($transmission);
            $car->setFuelType($fuel);
            $car->setSeats($seats);
            $car->setDescription($description);
            $car->setStatus($status);

            $manager->persist($car);
        }

        $manager->flush();
    }
}
