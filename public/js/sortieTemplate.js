const card = document.querySelector('.map-card');
const cardBody = card.querySelector('.card-body')

card.addEventListener('click', () => {
  cardBody.classList.toggle('closed')
})