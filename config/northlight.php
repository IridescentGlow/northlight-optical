<?php

/**
 * Static marketing content for the Northlight Optical pages (About, Services,
 * Promise, Testimonials, Contact). Kept out of the database intentionally —
 * this is editorial copy, not transactional data, so it ships with the code
 * instead of requiring a migration/seeder.
 */

return [

    'business' => [
        'name' => 'Northlight Optical',
        'phone' => '(503) 555-0142',
        'phone_href' => '+15035550142',
        'email' => 'hello@northlightoptical.com',
        'address_line1' => '428 Aurora Lane, Suite 100',
        'address_line2' => 'Portland, OR 97209',
        'maps_url' => 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode('Northlight Optical, 428 Aurora Lane Suite 100, Portland, OR 97209'),
    ],

    'hours' => [
        ['label' => 'Monday – Friday', 'value' => '9:00 AM – 6:00 PM'],
        ['label' => 'Saturday', 'value' => '10:00 AM – 4:00 PM'],
        ['label' => 'Sunday', 'value' => 'Closed'],
    ],

    'team' => [
        [
            'initials' => 'AO',
            'name' => 'Dr. Amara Okafor, OD',
            'role' => 'Lead Optometrist',
            'bio' => "Amara has practiced optometry in Portland for 15 years and leads our clinical team. She has a soft spot for pediatric exams and can usually talk a nervous six-year-old into sitting still for a retinal photo.",
            'languages' => ['English', 'Igbo'],
        ],
        [
            'initials' => 'PC',
            'name' => 'Dr. Priya Chandran, OD',
            'role' => 'Optometrist, Contact Lens Specialist',
            'bio' => "Priya joined Northlight after her residency in myopia management and specialty contact lens fittings. She runs our teen myopia-control program and is fluent in Hindi and Tamil.",
            'languages' => ['English', 'Hindi', 'Tamil'],
        ],
        [
            'initials' => 'MW',
            'name' => 'Marcus Webb',
            'role' => 'Lead Optician & Frame Stylist',
            'bio' => "Marcus has been fitting frames for 30 years and trains every new optician who joins us. He's used a wheelchair since a car accident in his twenties, and he redesigned our fitting counters himself so they work at any seated height.",
            'languages' => ['English'],
        ],
        [
            'initials' => 'SK',
            'name' => 'Soo-ah Kim',
            'role' => 'Patient Care Coordinator',
            'bio' => "Soo-ah runs our front desk, scheduling, and insurance verification. She grew up signing with her Deaf older brother and is conversational in ASL, so she's often the one bridging appointments for Deaf and hard-of-hearing patients.",
            'languages' => ['English', 'Korean', 'ASL (conversational)'],
        ],
        [
            'initials' => 'RD',
            'name' => 'Rosa Delgado',
            'role' => 'Optical Stylist',
            'bio' => "Rosa has styled frames at Northlight for over 20 years. She lives with macular degeneration herself, which is exactly why she's the one patients ask for when they're exploring low-vision tints and adaptive lenses.",
            'languages' => ['English', 'Spanish'],
        ],
        [
            'initials' => 'JR',
            'name' => 'Jordan Reyes',
            'role' => 'Optician, Kids\' Eyewear',
            'bio' => "Jordan grew up in a multilingual household and specializes in fitting eyewear for kids and first-time wearers. They organize our free fall vision-screening visits to neighborhood elementary schools.",
            'languages' => ['English', 'Amharic'],
        ],
    ],

    'services' => [
        [
            'icon' => 'eye',
            'title' => 'Comprehensive Eye Exams',
            'description' => 'Full exams for adults and seniors, including glaucoma screening and diabetic retinopathy checks, with a written copy of your prescription every time.',
        ],
        [
            'icon' => 'aperture',
            'title' => 'Prescription Eyeglasses',
            'description' => 'Single-vision, progressive, blue-light, and high-index lenses, fitted to frames from our in-house collection or a pair you bring in yourself.',
        ],
        [
            'icon' => 'target',
            'title' => 'Contact Lens Fittings',
            'description' => 'Soft, toric, and scleral lens fittings, plus hands-on insertion-and-removal training for first-time wearers.',
        ],
        [
            'icon' => 'smile',
            'title' => "Kids' Eyewear & Vision Screenings",
            'description' => 'Durable, flexible frames sized for small faces, and free vision screening days at local elementary schools every fall.',
        ],
        [
            'icon' => 'sun',
            'title' => 'Low Vision Consultations',
            'description' => 'One-on-one consultations for macular degeneration, glaucoma, and other low-vision conditions, with adaptive tints and magnification options.',
        ],
        [
            'icon' => 'tool',
            'title' => 'Frame Adjustments & Repairs',
            'description' => 'Free walk-in adjustments for the life of any frame purchased at Northlight, and paid repair service for frames from elsewhere.',
        ],
    ],

    'promise' => [
        [
            'icon' => 'life-buoy',
            'title' => 'Accessible by design',
            'description' => 'Step-free entrance, wide aisles, and height-adjustable fitting stations and exam chairs throughout the practice.',
        ],
        [
            'icon' => 'globe',
            'title' => 'Multilingual staff',
            'description' => 'Our team speaks Spanish, Korean, Hindi, Tamil, Igbo, and Amharic between us, with ASL available by request.',
        ],
        [
            'icon' => 'credit-card',
            'title' => 'Insurance & payment plans',
            'description' => 'We accept most major vision plans, HSA/FSA cards, and offer interest-free in-house payment plans and a sliding scale for uninsured patients.',
        ],
        [
            'icon' => 'eye',
            'title' => 'Low-vision specialists on staff',
            'description' => 'Rosa and Dr. Okafor both offer dedicated low-vision consultations for macular degeneration, glaucoma, and other conditions.',
        ],
        [
            'icon' => 'smile',
            'title' => 'Sensory-friendly appointments',
            'description' => 'Ask for a sensory-friendly exam for neurodivergent kids or adults: dimmed lighting, no waiting-room surprises, and extra time.',
        ],
        [
            'icon' => 'users',
            'title' => 'Rooted in the community',
            'description' => 'Free annual vision screening days for neighborhood schools, and discounted exams for local shelters and mutual aid groups.',
        ],
    ],

    'testimonials' => [
        [
            'quote' => "The exam room had a lowered chair and my optometrist just adjusted the equipment without me having to ask. First time that's ever happened.",
            'name' => 'Grace T.',
            'detail' => 'Patient since 2022',
        ],
        [
            'quote' => "Soo-ah signed with my dad through the whole appointment. He finally understood his own prescription instead of just nodding along.",
            'name' => 'Daniel P.',
            'detail' => 'Patient since 2023',
        ],
        [
            'quote' => "Rosa spent forty minutes with me comparing lens tints for my macular degeneration. Nobody had ever done that before.",
            'name' => 'Helen M.',
            'detail' => 'Patient since 2021',
        ],
        [
            'quote' => "My son has sensory sensitivities and they dimmed the lights without me even asking. He actually sat through the whole exam.",
            'name' => 'Priya S.',
            'detail' => 'Parent of a patient',
        ],
        [
            'quote' => "Found frames that actually fit my face shape for once, and with my VSP plan the total was way less than I expected.",
            'name' => 'Marcus L.',
            'detail' => 'Patient since 2024',
        ],
        [
            'quote' => "Jordan spoke Amharic with my grandmother through the entire visit. She finally felt like she understood her prescription, not just agreeing with everything.",
            'name' => 'Selam G.',
            'detail' => 'Patient since 2023',
        ],
    ],

    'contact_reasons' => [
        'General Inquiry',
        'Book an Eye Exam',
        'Prescription Question',
        'Frame Repair',
        'Insurance Question',
        'Other',
    ],

];
