<div class="container-fluid px-3">
	<div class="row">
		<div class="col-xl-6 col-xxl-5 d-flex">
			<div class="w-100">
				<div class="row">
					<div class="col-sm-6">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col mt-0">
										<h5 class="card-title">Sales</h5>
									</div>

									<div class="col-auto">
										<div class="stat text-primary">
											<i class="align-middle" data-feather="truck"></i>
										</div>
									</div>
								</div>
								<h1 class="mt-1 mb-3">2.382</h1>
								<div class="mb-0">
									<span class="text-danger"> <i class="mdi mdi-arrow-bottom-right"></i> -3.65% </span>
									<span class="text-muted">Since last week</span>
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col mt-0">
										<h5 class="card-title">Visitors</h5>
									</div>

									<div class="col-auto">
										<div class="stat text-primary">
											<i class="align-middle" data-feather="users"></i>
										</div>
									</div>
								</div>
								<h1 class="mt-1 mb-3">14.212</h1>
								<div class="mb-0">
									<span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i> 5.25% </span>
									<span class="text-muted">Since last week</span>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col mt-0">
										<h5 class="card-title">Earnings</h5>
									</div>

									<div class="col-auto">
										<div class="stat text-primary">
											<i class="align-middle" data-feather="dollar-sign"></i>
										</div>
									</div>
								</div>
								<h1 class="mt-1 mb-3">$21.300</h1>
								<div class="mb-0">
									<span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i> 6.65% </span>
									<span class="text-muted">Since last week</span>
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col mt-0">
										<h5 class="card-title">Orders</h5>
									</div>

									<div class="col-auto">
										<div class="stat text-primary">
											<i class="align-middle" data-feather="shopping-cart"></i>
										</div>
									</div>
								</div>
								<h1 class="mt-1 mb-3">64</h1>
								<div class="mb-0">
									<span class="text-danger"> <i class="mdi mdi-arrow-bottom-right"></i> -2.25% </span>
									<span class="text-muted">Since last week</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
        <div class="col-xl-12 col-xxl-12">
			<div class="card flex-fill w-100">
				<div class="card-header d-flex align-items-center">
					<h5 class="card-title mb-0">Shortcut</h5>
                    <button data-bs-placement="top" 
                            data-bs-toggle="modal" 
                            data-bs-target="#shortcutModel"
                            href="#" 
                            data-link="<?php echo $this->link_shortcut_form;?>/0"
                            class="align-middle ms-4 btn border border-1 button-shortcut" 
                            type="button">
                            <i class="fa-solid fa-plus"></i>
                    </button>
				</div>
				<div class="card-body shortcut-list ">
					<div class="row">
						<?php foreach($this->shortcuts as $shortcut) : ?>
							<div class="col-4">
								<?php if(isset($shortcut['childs']) && $shortcut['childs']): ?>
									<div>
										<h4 class="mb-2"><?php echo $shortcut['group']?></h4>
										<table class="table border-top border-1">
											<tbody>
												<?php foreach($shortcut['childs'] as $child):?>
													<tr>
														<td><a href="<?php echo $child['link'] ?>"><?php echo $child['name'] ?></a></td>
														<td class="action" width="100">
															<a class="fs-4 me-2 button-shortcut" 
																data-bs-placement="top" 
																data-bs-toggle="modal" 
																data-bs-target="#shortcutModel"
																data-link="<?php echo $this->link_shortcut_form. '/'. $child['id'];?>"
																data-id="<?php echo $child['id'];?>" 
																data-name_shortcut ="<?php echo $child['name'];?>" 
																data-link_shortcut ="<?php echo $child['link'];?>"
																data-group_shortcut ="<?php echo $child['group'];?>"
															>
																<i class="fa-solid fa-pen-to-square"></i>
															</a>
															<a class="fs-4" data-id="<?php echo $child['id'];?>" 
																data-name="<?php echo $child['name'];?>" 
																data-link="<?php echo $child['link'];?>"
																data-group="<?php echo $child['group'];?>"
															>
																<i class="fa-solid fa-trash"></i>
															</a>
														</td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
								<?php else: ?>
									<table class="table border-top border-1">
										<tbody>
											<tr>
												<td>
													<a href="<?php echo $shortcut['link']?>"><?php echo $shortcut['name']?></a>
												</td>
												<td class="action" width="100">
													<a class="fs-4 me-2 button-shortcut" 
														data-bs-placement="top" 
														data-bs-toggle="modal" 
														data-bs-target="#shortcutModel"
														data-link="<?php echo $this->link_shortcut_form. '/'. $shortcut['id'];?>"
														data-id="<?php echo $shortcut['id'];?>" 
														data-name_shortcut ="<?php echo $shortcut['name'];?>" 
														data-link_shortcut ="<?php echo $shortcut['link'];?>"
														data-group_shortcut ="<?php echo $shortcut['group'];?>"
													>
														<i class="fa-solid fa-pen-to-square"></i>
													</a>
													<a class="fs-4" data-id="<?php echo $shortcut['id'];?>" 
														data-name="<?php echo $shortcut['name'];?>" 
														data-link="<?php echo $shortcut['link'];?>"
														data-group="<?php echo $shortcut['group'];?>"
													>
														<i class="fa-solid fa-trash"></i>
													</a>
												</td>
											</tr>
										</tbody>
									</table>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $this->renderWidget('shortcut::form');?>
<?php echo $this->render('pnote.javascript');?>