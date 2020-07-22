/**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
   /**
     * @var Security
     */
    private $security;

     /**
     * UserCrudController constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Security $security
     */
    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        Security $security
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->security = $security;

        // get the user id from the logged in user
        if (null !== $this->security->getUser()) {
            $this->password = $this->security->getUser()->getPassword();
        }
    }
 /**
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        $password = TextField::new('password')
            ->setFormType(PasswordType::class)
            ->setFormTypeOption('empty_data', '')
            ->setRequired(false)
            ->setHelp('If the right is not given, leave the field blank.');

        switch ($pageName) {
            case Crud::PAGE_INDEX:
               return [
                    $password,
                ];
                break;
            case Crud::PAGE_DETAIL:
                return [
                    $password,
                ];
                break;
            case Crud::PAGE_NEW:
               return [
                    $password,
                ];
                break;
            case Crud::PAGE_EDIT:
                return [
                    $password,
                ];
                break;
        }

    }

 /**
     *
     * @param EntityManagerInterface $entityManager
     * @param $entityInstance
     */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {


        // set new password with encoder interface
        if (method_exists($entityInstance, 'setPassword')) {
            $clearPassword = trim($this->get('request_stack')->getCurrentRequest()->request->all('User')['password']);

            // if user password not change save the old one
            if (isset($clearPassword) === true && $clearPassword === '') {
                $entityInstance->setPassword($this->password);
            } else {
                $encodedPassword = $this->passwordEncoder->encodePassword($this->getUser(), $clearPassword);
                $entityInstance->setPassword($encodedPassword);
            }
        }

        parent::updateEntity($entityManager, $entityInstance);
    }