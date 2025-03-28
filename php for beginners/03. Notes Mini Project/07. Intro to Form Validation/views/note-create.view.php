<?php require("partials/head.php") ?>
<?php require("partials/nav.php") ?>
<?php require("partials/banner.php") ?>

<main>
    <div class="mx-auto max-w-7xl my-6 px-4 py-1 sm:px-6 lg:px-8">
        <div class="divide-y divide-gray-900/10">
            <div class="grid grid-cols-1 gap-x-8 gap-y-8 py-10 md:grid-cols-3">
                <div class="px-4 sm:px-0">
                    <h2 class="text-base/7 font-semibold text-gray-900">Create a Note</h2>
                    <p class="mt-1 text-sm/6 text-gray-600">Use this area to create a new note for youself!</p>
                </div>

                <form method="POST" class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
                    <div class="px-4 py-6 sm:p-8">
                        <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="col-span-full">
                                <label for="body" class="block text-sm/6 font-medium text-gray-900">New Note</label>
                                <div class="mt-2">
                                    <textarea
                                        name="body"
                                        id="body"
                                        placeholder="Here's an idea..."
                                        rows="3"
                                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                        required
                                        ><?= $_POST['body'] ?? '' ?></textarea>
                                        <?php if(isset($errors['body'])) : ?>
                                            <p class="text-sm text-red-500 mt-2"><?= $errors['body'] ?></p>
                                        <?php endif ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
                        <button type="reset" class="text-sm/6 font-semibold text-gray-900">Cancel</button>
                        <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php require("partials/footer.php") ?>