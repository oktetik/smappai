<!DOCTYPE html>
<html lang="<?= $language ?? 'en' ?>"  class="h-full" data-kt-theme="true" data-kt-theme-mode="light" dir="ltr">
<?php echo $this->include('admin/layout/_head'); ?>
<body class="antialiased flex h-full text-base text-foreground bg-background demo1 kt-sidebar-fixed kt-header-fixed">
    <script>
   const defaultThemeMode = 'system'; // light|dark|system
			let themeMode;

			if (document.documentElement) {
				if (localStorage.getItem('kt-theme')) {
					themeMode = localStorage.getItem('kt-theme');
				} else if (
					document.documentElement.hasAttribute('data-kt-theme-mode')
				) {
					themeMode =
						document.documentElement.getAttribute('data-kt-theme-mode');
				} else {
					themeMode = defaultThemeMode;
				}

				if (themeMode === 'system') {
					themeMode = window.matchMedia('(prefers-color-scheme: dark)').matches
						? 'dark'
						: 'light';
				}

				document.documentElement.classList.add(themeMode);
			}
  </script>

  <div class="flex grow">
    <?php echo $this->include('admin/layout/_sidebar'); ?>
    <div class="kt-wrapper flex grow flex-col">
        <?php echo $this->include('admin/layout/_header'); ?>
        <main class="grow pt-5" id="content" role="content">
            <div class="kt-container-fixed" id="contentContainer"></div>
            <div class="kt-container-fixed">
                <div class="grid gap-5 lg:gap-7.5">
                    <?= $this->renderSection('content') ?>
                </div>
            </div>
        </main>
        <?php echo $this->include('admin/layout/_footer'); ?>
    </div>
  </div>

    <?php echo $this->include('admin/layout/_modals'); ?>
    <?php echo $this->include('admin/layout/_scripts'); ?>
</body>
</html>