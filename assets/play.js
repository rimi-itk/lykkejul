/* global winwheel_settings Audio */

import { gsap, CSSPlugin } from "gsap/all";

require('./play.scss')

const $ = require('jquery')

const bootstrap = require('bootstrap')
import { Modal } from 'bootstrap';
const modal = new bootstrap.Modal(document.getElementById('winModal'), {})
const Winwheel = require('./lib/Winwheel')

gsap.registerPlugin(CSSPlugin)

import taDaPath from './audio/tada.mp3'
import tickPath from './audio/tick.mp3'
import backgroundMusicPathDefault from './audio/beginning_look_like_christmas.mp3'
import backgroundMusicPathHrMortensen from './audio/Hr_Mortensens_fødselsdag.mp3'

const playOptions = play_options

const now = play_options.date ? new Date(play_options.date) : new Date()
const day = now.getDate()

const backgroundMusicPath = 18 === day ? backgroundMusicPathHrMortensen : backgroundMusicPathDefault

// @TODO Do this the right way!
window.TweenMax = require('gsap').TweenMax

const getRandomInt = (min, max) => {
  min = Math.ceil(min)
  max = Math.floor(max)

  return Math.floor(Math.random() * (max - min)) + min // The maximum is exclusive and the minimum is inclusive
}

$(() => {
  if (playOptions.grand_prize_day !== day) {
    $('#grand-prize').hide()
  }

  const $select = $('#play_player')
  const $options = $select.find('option')
  const random = getRandomInt(0, $options.length)
  $options.eq(random).prop('selected', true)

  const backgroundMusic = new Audio(backgroundMusicPath)

  $('#play-music').on('click', () => {
    backgroundMusic.play()
  })

  const taDa = new Audio(taDaPath)

  const alertPrize = () => {
    $('#pointer').removeClass('wheel-spinning')

    taDa.pause()
    taDa.currentTime = 0
    taDa.play()

    const segment = wheel.getIndicatedSegment()

    $('#winner-name').html(segment.text)

    $select.val(segment.id)

    modal.show()
  }

  const audio = new Audio(tickPath)

  const playSound = () => {
    // Stop and rewind the sound (stops it if already playing).
    audio.pause()
    audio.currentTime = 0

    // Play the sound.
    audio.play()
  }

  const winwheelOptions = winwheel_settings.options

  const winWheelArguments = Object.assign(winwheelOptions || {}, {
    animation: // Note animation properties passed in constructor parameters.
        {
          type: 'spinToStop', // Type of animation.
          duration: 10, // How long the animation is to take in seconds.
          spins: 2, // The number of complete 360 degree rotations the wheel is to do.
          callbackFinished: alertPrize,
          callbackSound: playSound
          // soundTrigger: 'pin'
        },
    canvasId: 'wheel',
    numSegments: winwheelOptions.segments.length,
    pins: {
      number: 2 * winwheelOptions.segments.length
    },
    pointerAngle: 90
    // 'pointerGuide': {'display': true, 'strokeStyle': 'red', 'lineWidth': 3},
  })

  // Make wheel canvas fill wrapper.
  const $wheel = $('#wheel')
  const $wrapper = $wheel.parent()
  $wheel.attr('width', $wrapper.width() + 'px').attr('height', $wrapper.height() + 'px')

  const wheel = new Winwheel(winWheelArguments)

  // Apply colors to segments.
  const colors = winwheel_settings.colors
  wheel.segments.forEach((segment, index) => {
    if (segment) {
      segment.fillStyle = colors[index % colors.length]
    }
  })
  wheel.draw()

  const spinTheWheel = () => {
    backgroundMusic.pause()

    $select.val('')

    wheel.stopAnimation(false)
    wheel.rotationAngle = 0
    wheel.startAnimation()

    $('#pointer').addClass('wheel-spinning')
  }

  $('.spin-the-wheel').on('click', spinTheWheel)

  $(document).on('keypress', (event) => {
    const modalOpen = modal._isShown
    switch (event.code) {
      case 'Space':
        if (!modalOpen) {
          spinTheWheel()
        }
        break
      case 'Enter':
        if (modalOpen && $select.val()) {
          $('#winModal').find('form').submit()
        }
        break
    }
  })
})
