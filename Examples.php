<?php

abstract class Vehicle {}

class Truck extends Vehicle {}

final class Car extends Vehicle implements CarInterface, VehicleInterface {}

final class Bus extends Vehicle implements BusInterface, VehicleInterface {}
