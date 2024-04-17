<?php

namespace App\Virtual\Requests\Contract;

/**
 * @OA\Schema(
 *     title="Создание договора для юридического лица",
 *     type="object",
 *     required={"inn", "kpp", "ogrn", "company_name", "legal_address", "actual_address", "contact_face", "job_title", "phone", "email", "bik", "checking_account", "bank_name", "correspondent_account", "pick_up", "is_same_legal_address"},
 * )
 */
class ContractLegalEntityRequest
{
    /**
     * @OA\Property(
     *     property="inn",
     *     format="number",
     *     title="ИНН",
     *     description="ИНН",
     *     example="1234567890",
     * )
     */
    public $inn;

    /**
     * @OA\Property(
     *     property="kpp",
     *     format="number",
     *     title="КПП",
     *     description="КПП",
     *     example="1234567890",
     * )
     */
    public $kpp;

    /**
     * @OA\Property(
     *     property="ogrn",
     *     format="number",
     *     title="ОГРН",
     *     description="ОГРН",
     *     example="1234567890",
     * )
     */
    public $ogrn;

    /**
     * @OA\Property(
     *     property="company_name",
     *     format="string",
     *     title="Название компании",
     *     description="Название компании",
     *     example="ООО Ромашка",
     * )
     */
    public $companyName;

    /**
     * @OA\Property(
     *     property="legal_address",
     *     format="string",
     *     title="Юридический адрес",
     *     description="Юридический адрес",
     *     example="г. Вологда, ул. Кукушкина",
     * )
     */
    public $legalAddress;

    /**
     * @OA\Property(
     *     property="actual_address",
     *     format="string",
     *     title="Физический адрес",
     *     description="Физический адрес",
     *     example="г. Вологда, ул. Кукушкина",
     * )
     */
    public $actualAddress;

    /**
     * @OA\Property(
     *     property="contact_face",
     *     format="string",
     *     title="Контактное лицо",
     *     description="Контактное лицо",
     *     example="Игорь Лебединое-Озеро",
     * )
     */
    public $contactFace;

    /**
     * @OA\Property(
     *     property="job_title",
     *     format="string",
     *     title="Должность",
     *     description="Должность",
     *     example="Булкоед",
     * )
     */
    public $jobTitle;

    /**
     * @OA\Property(
     *     property="phone",
     *     type="string",
     *     title="Телефон",
     *     description="Телефон",
     *     example="1234567890",
     * )
     */
    public $phone;

    /**
     * @OA\Property(
     *     property="email",
     *     format="string",
     *     title="Email",
     *     description="Email",
     *     example="bulkoedstvo@ne-porok.ru",
     * )
     */
    public $email;

    /**
     * @OA\Property(
     *     property="bik",
     *     format="number",
     *     title="Бик",
     *     description="Бик",
     *     example="123456789",
     * )
     */
    public $bik;

    /**
     * @OA\Property(
     *     property="checking_account",
     *     format="string",
     *     title="Расчетный счет",
     *     description="Расчетный счет",
     *     example="123456789",
     * )
     */
    public $checkingAccount;

    /**
     * @OA\Property(
     *     property="bank_name",
     *     format="string",
     *     title="Название банка",
     *     description="Название банка",
     *     example="Булка-банк",
     * )
     */
    public $bankName;

    /**
     * @OA\Property(
     *     property="correspondent_account",
     *     format="number",
     *     title="Корреспондентский счет",
     *     description="Корреспондентский счет",
     *     example="123456789012342351324",
     * )
     */
    public $correspondentAccount;

    /**
     * @OA\Property(
     *     property="pick_up",
     *     format="string",
     *     title="Как вы будете забирать документы",
     *     description="Как вы будете забирать документы (not_need,need_original,need_email)",
     *     example="need_email",
     * )
     */
    public $pickUp;

    /**
     * @OA\Property(
     *     property="is_same_legal_address",
     *     format="boolean",
     *     title="Совпадает с юридическим адресом",
     *     description="Совпадает с юридическим адресом",
     *     example=true,
     * )
     */
    public $isSameLegalAddress;

}
