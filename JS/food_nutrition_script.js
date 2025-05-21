const dietPlan = [];
let selectedFood = null;

const recipes = {
    '100g Rice (White)': {
        title: 'Rice Recipe',
        ingredients: ['1 cup rice', '2 cups water', 'Salt to taste'],
        instructions: 'Rinse the rice thoroughly. Boil water, add rice and salt. Cook for 15-20 minutes until tender.',
        nutrition: 'Calories: 130 kcal, Protein: 7g per 100g serving.',
    },
    '100g Rice (Brown)': {
        title: 'Brown Rice Recipe',
        ingredients: ['1 cup brown rice', '2.5 cups water', 'Salt to taste'],
        instructions: 'Rinse the rice thoroughly. Boil water, add rice and salt. Cook for 30-40 minutes until tender.',
        nutrition: 'Calories: 111 kcal, Protein: 7g per 100g serving.',
    },
    // ...add recipes for other food items...
};

function selectFoodItem(foodItem, protein, calories, recipeKey) {
    selectedFood = { foodItem, protein, calories };
    if (recipes[recipeKey]) {
        showRecipeModal(recipeKey);
    } else {
        document.getElementById('selected-food-item').innerText = `Food: ${foodItem}, Protein: ${protein}, Calories: ${calories}`;
        document.getElementById('serving-size-modal').style.display = 'block';
    }
}

function showRecipeModal(recipeKey) {
    const recipe = recipes[recipeKey];
    document.getElementById('recipe-title').innerText = recipe.title;
    document.getElementById('recipe-ingredients').innerHTML = recipe.ingredients.map(ingredient => `<li>${ingredient}</li>`).join('');
    document.getElementById('recipe-instructions').innerText = recipe.instructions;
    document.getElementById('recipe-nutrition').innerText = recipe.nutrition;
    document.getElementById('recipe-modal').style.display = 'block';
}

function closeRecipeModal() {
    document.getElementById('recipe-modal').style.display = 'none';
}

function confirmFoodItem() {
    const servingSize = document.getElementById('serving-size').value;
    const adjustedProtein = adjustNutrient(selectedFood.protein, servingSize);
    const adjustedCalories = adjustNutrient(selectedFood.calories, servingSize);

    dietPlan.push({
        foodItem: `${servingSize}g of ${selectedFood.foodItem}`,
        protein: adjustedProtein,
        calories: adjustedCalories,
    });

    alert(`${servingSize}g of ${selectedFood.foodItem} has been added to your diet plan.`);
    closeServingSizeModal();
}

function adjustNutrient(value, servingSize) {
    const numericValue = parseFloat(value);
    return `${((numericValue / 100) * servingSize).toFixed(1)}g`;
}

function viewDietPlan() {
    const modal = document.getElementById('diet-plan-modal');
    const list = document.getElementById('diet-plan-list');
    list.innerHTML = '';

    dietPlan.forEach((item, index) => {
        const listItem = document.createElement('li');
        listItem.style.marginBottom = '10px';
        listItem.innerHTML = `
            ${item.foodItem} - Protein: ${item.protein}, Calories: ${item.calories}
            <button onclick="removeFromDietPlan(${index})" class="secondary-button" style="margin-left: 10px;">Remove</button>
        `;
        list.appendChild(listItem);
    });

    modal.style.display = 'block';
}

function removeFromDietPlan(index) {
    dietPlan.splice(index, 1);
    viewDietPlan();
}

function closeServingSizeModal() {
    document.getElementById('serving-size-modal').style.display = 'none';
}

function closeDietPlan() {
    document.getElementById('diet-plan-modal').style.display = 'none';
}

document.getElementById('view-diet-plan').addEventListener('click', viewDietPlan);
