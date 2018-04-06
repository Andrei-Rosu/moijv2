<?php


namespace App\DataTransformers;


use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TagTransformer implements DataTransformerInterface
{

    /**
     * @var TagRepository
     */

    private $tagRepo;
    public function __construct(TagRepository $tagRepo)
    {
        $this->tagRepo = $tagRepo;
    }

    /**
     * Transforms a value from the original representation to a transformed representation.
     *
     * This method is called on two occasions inside a form field:
     *
     * 1. When the form field is initialized with the data attached from the datasource (object or array).
     * 2. When data from a request is submitted using {@link Form::submit()} to transform the new input data
     *    back into the renderable format. For example if you have a date field and submit '2009-10-10'
     *    you might accept this value because its easily parsed, but the transformer still writes back
     *    "2009/10/10" onto the form field (for further displaying or other purposes).
     *
     * This method must be able to deal with empty values. Usually this will
     * be NULL, but depending on your implementation other empty values are
     * possible as well (such as empty strings). The reasoning behind this is
     * that value transformers must be chainable. If the transform() method
     * of the first value transformer outputs NULL, the second value transformer
     * must be able to process that value.
     *
     * By convention, transform() should return an empty string if NULL is
     * passed.
     *
     * @param mixed $value The value in the original representation
     *
     * @return mixed The value in the transformed representation
     *
     * @throws TransformationFailedException when the transformation fails
     */
    public function transform($tagCollection)
    {
        // array_map(function, array)
        $tagArray = $tagCollection->toArray();
        $nameArray = array_map(function ($tag){ return $tag->getName();}, $tagArray);

        return implode(',', $nameArray);
    }

    /**
     * Transforms a value from the transformed representation to its original
     * representation.
     *
     * This method is called when {@link Form::submit()} is called to transform the requests tainted data
     * into an acceptable format for your data processing/model layer.
     *
     * This method must be able to deal with empty values. Usually this will
     * be an empty string, but depending on your implementation other empty
     * values are possible as well (such as NULL). The reasoning behind
     * this is that value transformers must be chainable. If the
     * reverseTransform() method of the first value transformer outputs an
     * empty string, the second value transformer must be able to process that
     * value.
     *
     * By convention, reverseTransform() should return NULL if an empty string
     * is passed.
     *
     * @param mixed $value The value in the transformed representation
     *
     * @return mixed The value in the original representation
     *
     * @throws TransformationFailedException when the transformation fails
     */
    public function reverseTransform($tagString)
    {
        $tagArray = array_unique(explode(',', $tagString));
        $tagCollection = new ArrayCollection();
        foreach($tagArray as $tagName){
            $tagCollection->add($this->tagRepo->getCorrespondingTag($tagName));
        }
        return $tagCollection;

    }
}