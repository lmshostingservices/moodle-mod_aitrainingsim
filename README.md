# AI Training Simulation (`mod_aitrainingsim`)

An activity module for Moodle that creates immersive, AI-generated workplace scenario simulations.

Teachers configure a scenario once — job role, workplace setting, learning objective, difficulty level, number of steps. GPT-4o builds a branching decision simulation; DALL-E 3 paints each scene. Students navigate a split-screen simulation and receive a personalised skill-radar debrief.

## Key features

- AI-generated branching scenarios (GPT-4o) from a single teacher prompt
- DALL-E 3 scene painting for every decision step
- Split-screen simulation player with free-text response evaluation
- Skill radar debrief after completion
- Moodle gradebook integration — simulation scores pushed automatically
- Standard Moodle activity completion conditions
- Full GDPR Privacy API provider

## Requirements

- Moodle 4.4 – 5.3
- PHP 7.4+
- **local_aiconfig** — provides OpenAI API key and LMS Labs credit metering
- LMS Labs AI credits (consumed per simulation generation)
- Outbound HTTPS to OpenAI API endpoints

## Credit usage

A 6-step simulation costs approximately 40–55 LMS Labs credits (scenario generation + DALL-E 3 images). Student replays do not consume additional credits.

## Installation

1. Download the ZIP from lms-labs.com → Plugins → AI Training Simulation
2. Moodle → Site administration → Plugins → Install plugins → upload ZIP
3. Ensure `local_aiconfig` is installed and OpenAI API key is configured
4. Add the activity to any course, configure the scenario settings, save

## Compatibility

Moodle 4.4 – 5.3 · PHP 7.4+ · MySQL 5.7+ or PostgreSQL 12+

## Licence

GNU GPL v3 or later — see [COPYING](https://www.gnu.org/licenses/gpl-3.0.html)

## Support

support@lmshostingservices.com · https://lms-labs.com/docs/ai-training-simulation
