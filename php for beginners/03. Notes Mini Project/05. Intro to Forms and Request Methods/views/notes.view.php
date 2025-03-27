<?php require("partials/head.php") ?>

<?php require("partials/nav.php") ?>

<?php require("partials/banner.php") ?>

<main>
	<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
		<div>
			<p>Welcome to the notes page</p>
		</div>

		<ul>
			<?php foreach ($notes as $note) : ?>
				<li class="mx-auto max-w-7xl py-1">
					<a href="/note?id=<?= $note['id'] ?>" class="text-blue-500 hover:underline">
						<?= $note['body']; ?>
					</a>
				</li>
			<?php endforeach ?>
		</ul>

		<p class="mt-6">
			<a href="/note/create" class="text-blue-500 hover:underline">Create Note</a>
		</p>
	</div>
</main>

<?php require("partials/footer.php") ?>