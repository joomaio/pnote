<?php echo $this->renderWidget('core::notification'); ?>
<div class="container-fluid align-items-center row justify-content-center mx-auto pt-3">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-sm-12">
            <table class="table table-bordered preview-note">
                <thead>
                    <tr class="border-top-0 list-products">
                        <th class="border-0 text-center" scope="col" width="200px">
                        </th>
                        <?php if(isset($this->data['products']) && $this->data['products']) :  ?>
                            <?php foreach($this->data['products'] as $product) : ?>
                                <th scope="col" class="border-top product-item position-relative">
                                    <a target="_blank" 
                                        <?php if($product['link']): ?>
                                        href="<?php echo strpos($product['link'], 'http') !== false ? $product['link'] : 'https://'. $product['link'] ?>">
                                        <?php else: ?>
                                        href="#">
                                        <?php endif; ?>
                                        <div class="content p-0">
                                            <?php echo $product['title'];?>
                                        </div>
                                    </a>
                                </th>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <th scope="col" class="border-top product-item position-relative">
                                <div class="content p-0">
                                </div>
                            </th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="feature-list">
                    <?php if(isset($this->data['products']) && $this->data['products']) :  ?>
                        <?php $product = $this->data['products'][0]; ?>
                        <?php foreach($product['features'] as $index => $feature) : ?>
                            <tr class="feature-item">
                                <th scope="row" class="feature-title position-relative">
                                    <div class="content p-0">
                                        <?php echo $feature['title']; ?>
                                    </div>
                                </th>
                                <?php for ($i=0; $i < count($this->data['products']) ; $i++) : ?>
                                    <td class="feature-content">
                                        <div class="des">
                                            <?php echo  isset($this->data['products'][$i]['features'][$index]['content']) ? $this->data['products'][$i]['features'][$index]['content'] : '' ?>
                                        </div>
                                    </td>
                                <?php endfor; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr class="feature-item">
                            <th scope="row" class="feature-title position-relative">
                                <div class="content p-0">
                                </div>
                                <input type="text" name="feature-title" class="form-control d-none">
                                <a class="remove-feature position-absolute" href="">
                                    <i class="fa-solid fa-xmark"></i>
                                </a>
                            </th>
                            <td class="feature-content">
                                <div class="des">
                                </div>
                                <input type="hidden" name="feature-content">
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
