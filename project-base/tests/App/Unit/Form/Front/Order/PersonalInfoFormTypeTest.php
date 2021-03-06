<?php

declare(strict_types=1);

namespace Tests\App\Unit\Form\Front\Order;

use App\Form\Front\Order\PersonalInfoFormType;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Model\Country\Country;
use Shopsys\FrameworkBundle\Model\Country\CountryFacade;
use Shopsys\FrameworkBundle\Model\Customer\User\CurrentCustomerUser;
use Shopsys\FrameworkBundle\Model\Heureka\HeurekaFacade;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class PersonalInfoFormTypeTest extends TypeTestCase
{
    /**
     * @var \Shopsys\FrameworkBundle\Model\Country\CountryFacade|\PHPUnit\Framework\MockObject\MockObject
     */
    private $countryFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Heureka\HeurekaFacade|\PHPUnit\Framework\MockObject\MockObject
     */
    private $heurekaFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Domain\Domain|\PHPUnit\Framework\MockObject\MockObject
     */
    private $domain;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\User\CurrentCustomerUser|\PHPUnit\Framework\MockObject\MockObject
     */
    private $currentCustomerUser;

    /**
     * @return array
     */
    public function getTermsAndConditionsAgreementIsMandatoryData()
    {
        return [
            [$this->getPersonalInfoFormData(true), true],
            [$this->getPersonalInfoFormData(false), false],
        ];
    }

    /**
     * @param array $personalInfoFormData
     * @param bool $isExpectedValid
     * @dataProvider getTermsAndConditionsAgreementIsMandatoryData
     */
    public function testTermsAndConditionsAgreementIsMandatory(array $personalInfoFormData, $isExpectedValid)
    {
        $this->disableHeurekaShopCertification();

        $personalInfoForm = $this->createPersonalInfoForm();

        $personalInfoForm->submit($personalInfoFormData);

        $this->assertSame($isExpectedValid, $personalInfoForm->isValid());
    }

    /**
     * @param bool $legalConditionsAgreement
     * @return array
     */
    private function getPersonalInfoFormData($legalConditionsAgreement)
    {
        $personalInfoFormData = [];
        $personalInfoFormData['firstName'] = 'test';
        $personalInfoFormData['lastName'] = 'test';
        $personalInfoFormData['email'] = 'test@test.cz';
        $personalInfoFormData['telephone'] = '123456789';
        $personalInfoFormData['street'] = 'test';
        $personalInfoFormData['city'] = 'test';
        $personalInfoFormData['postcode'] = '12345';
        $personalInfoFormData['country'] = 1;
        $personalInfoFormData['legalConditionsAgreement'] = $legalConditionsAgreement;
        $personalInfoFormData['newsletterSubscription'] = false;

        return $personalInfoFormData;
    }

    public function testHeurekaShopCertificationActivatedAndDisallowedByUser()
    {
        $this->enableHeurekaShopCertification();
        $personalInfoFormData = $this->getPersonalInfoFormData(true);
        $personalInfoFormData['disallowHeurekaVerifiedByCustomers'] = true;

        $personalInfoForm = $this->createPersonalInfoForm();

        $personalInfoForm->submit($personalInfoFormData);

        $data = $personalInfoForm->getData();
        $this->assertTrue($data->disallowHeurekaVerifiedByCustomers);
    }

    /**
     * @return array
     */
    protected function getExtensions(): array
    {
        return [
            new ValidatorExtension(Validation::createValidator()),
            new PreloadedExtension(
                [new PersonalInfoFormType(
                    $this->countryFacade,
                    $this->heurekaFacade,
                    $this->domain,
                    $this->currentCustomerUser
                )],
                []
            ),
        ];
    }

    protected function setUp(): void
    {
        $countryMock = $this->createMock(Country::class);
        $countryMock->method('getId')->willReturn(1);

        $this->countryFacade = $this->createMock(CountryFacade::class);
        $this->countryFacade->method('getAllEnabledOnDomain')->willReturn([$countryMock]);

        $this->domain = $this->createMock(Domain::class);
        $this->domain->method('getId')->willReturn(Domain::FIRST_DOMAIN_ID);

        $this->currentCustomerUser = $this->createMock(CurrentCustomerUser::class);

        $this->heurekaFacade = $this->createMock(HeurekaFacade::class);

        parent::setUp();
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createPersonalInfoForm(): FormInterface
    {
        return $this->factory->create(PersonalInfoFormType::class, null, [
            'domain_id' => 1,
        ]);
    }

    private function disableHeurekaShopCertification(): void
    {
        $this->heurekaFacade->method('isHeurekaShopCertificationActivated')->willReturn(false);
    }

    private function enableHeurekaShopCertification(): void
    {
        $this->heurekaFacade->method('isHeurekaShopCertificationActivated')->willReturn(true);
    }
}
