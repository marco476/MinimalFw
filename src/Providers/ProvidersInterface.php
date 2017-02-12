<?php
namespace Providers;

interface ProvidersInterface
{
    //Execute instructions for a specific provider.
    public function startProvide(array $options);

    //Check required parameters for start di provide
    public function checkRequiredParameters(array $options): bool;

    //Return the name of class without namespace
    public function getClassNameWithoutNamespace(): string;
}
