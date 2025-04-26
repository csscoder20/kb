<?php

namespace Database\Seeders;

use App\Models\TermsAndConditions;
use Illuminate\Database\Seeder;

class TermsAndConditionsSeeder extends Seeder
{
    public function run(): void
    {
        TermsAndConditions::create([
            'type' => 'pdf',
            'title' => 'Terms and Conditions for MoP Preview',
            'content' => [
                [
                    'title' => 'Preview Access',
                    'items' => [
                        ['item' => 'Preview is intended for quick reference and validation purposes only'],
                        ['item' => 'The preview feature does not grant permission to save or distribute the content']
                    ]
                ],
                [
                    'title' => 'Content Protection',
                    'items' => [
                        ['item' => 'Screenshots and screen recording of the previewed content are strictly prohibited'],
                        ['item' => 'The content remains confidential and proprietary to the organization']
                    ]
                ],
                [
                    'title' => 'Usage Guidelines',
                    'items' => [
                        ['item' => 'Preview access is logged and monitored for security purposes'],
                        ['item' => 'Multiple unauthorized preview attempts may result in account restriction']
                    ]
                ]
            ]
        ]);

        TermsAndConditions::create([
            'type' => 'docx',
            'title' => 'Terms and Conditions for MoP Download',
            'content' => [
                [
                    'title' => 'Download Responsibility',
                    'items' => [
                        'Downloaded documents are for authorized professional use only',
                        'You are responsible for maintaining the confidentiality of downloaded content',
                        'Local copies must be securely stored and properly disposed of when no longer needed'
                    ]
                ],
                [
                    'title' => 'Distribution Restrictions',
                    'items' => [
                        'Sharing or forwarding downloaded documents is strictly prohibited',
                        'Documents must not be uploaded to public platforms or cloud storage',
                        'Distribution to third parties requires explicit management approval'
                    ]
                ],
                [
                    'title' => 'Security Measures',
                    'items' => [
                        'Downloads are tracked and logged for security compliance',
                        'Unauthorized distribution will result in immediate access revocation',
                        'Regular audits may be conducted to ensure compliance'
                    ]
                ]
            ]
        ]);

        // New Upload policy terms
        TermsAndConditions::create([
            'type' => 'upload',
            'title' => 'Terms and Conditions for Report Sharing and Distribution',
            'content' => [
                [
                    'title' => 'Confidentiality',
                    'items' => [
                        ['item' => 'All reports uploaded contain confidential technical information and are strictly for internal use only'],
                        ['item' => 'Reports may contain sensitive customer infrastructure details that must be protected']
                    ]
                ],
                [
                    'title' => 'Usage Rights',
                    'items' => [
                        ['item' => 'Reports can only be accessed by authorized personnel with valid login credentials'],
                        ['item' => 'Downloading and viewing rights are restricted to team members involved in technical implementations']
                    ]
                ],
                [
                    'title' => 'Distribution Restrictions',
                    'items' => [
                        ['item' => 'Reports must not be shared with external parties without proper authorization'],
                        ['item' => 'Distribution to customers requires explicit approval from management'],
                        ['item' => 'Sharing through unofficial channels (personal email, messaging apps) is prohibited']
                    ]
                ],
                [
                    'title' => 'Content Responsibility',
                    'items' => [
                        ['item' => 'Uploaded reports must be accurate and follow the standard MoP format'],
                        ['item' => 'Technical details, configurations, and procedures must be verified before upload'],
                        ['item' => 'You are responsible for ensuring no sensitive credentials are included in the reports']
                    ]
                ],
                [
                    'title' => 'Intellectual Property',
                    'items' => [
                        ['item' => 'All uploaded reports remain the intellectual property of the company'],
                        ['item' => 'Methods, procedures, and technical solutions described in reports cannot be used for commercial purposes without authorization']
                    ]
                ],
                [
                    'title' => 'Compliance',
                    'items' => [
                        ['item' => 'Reports must comply with data protection regulations and security policies'],
                        ['item' => 'Non-compliance may result in restricted access or disciplinary action']
                    ]
                ]
            ]
        ]);

        TermsAndConditions::create([
            'type' => 'title_guidelines',
            'title' => 'Title Guidelines',
            'content' => [
                [
                    'title' => 'Title Format Guidelines',
                    'items' => [
                        ['item' => 'The title should follow the full title of the MoP file'],
                        ['item' => 'Include: Action (Upgrade, Install, Configure, etc.)'],
                        ['item' => 'Include: System/Product name'],
                        ['item' => 'Include: Version details (if applicable)'],
                        ['item' => 'Include: Customer name'],
                    ]
                ],
                [
                    'title' => 'Title Example',
                    'items' => [
                        ['item' => 'Upgrade BIG-IP F5 from version 17.1.1.2 to version 17.1.2.1 - Indocement Tunggal Prakarsa'],
                    ]
                ]
            ]
        ]);
        // Info Modal Contents
        TermsAndConditions::create([
            'type' => 'customer_selection_guidelines',
            'title' => 'Customer Selection Guidelines',
            'content' => [
                [
                    'title' => 'Customer Selection Guidelines',
                    'items' => [
                        ['item' => 'Type to search existing customers'],
                        ['item' => 'Select from dropdown list'],
                        ['item' => 'Or type a new customer name to create'],
                        ['item' => 'Ensure correct spelling for existing customers'],
                        ['item' => 'New customers will be automatically created'],
                        ['item' => 'Customer names must be unique']
                    ]
                ]
            ]
        ]);

        TermsAndConditions::create([
            'type' => 'technology_selection_guidelines',
            'title' => 'Technology Selection Guidelines',
            'content' => [
                [
                    'title' => 'Technology Selection Guidelines',
                    'items' => [
                        ['item' => 'Select up to 2 technologies that are relevant to this MoP'],
                        ['item' => 'Minimum: 1 technology'],
                        ['item' => 'Maximum: 2 technologies'],
                        ['item' => 'Choose the most relevant technologies'],
                        ['item' => 'Choose technologies that best represent the main focus of your MoP']
                    ]
                ]
            ]
        ]);

        TermsAndConditions::create([
            'type' => 'file_mop_guidelines',
            'title' => 'File MOP Guidelines',
            'content' => [
                [
                    'title' => 'File Upload Guidelines',
                    'items' => [
                        ['item' => 'Format: Microsoft Word (.docx)'],
                        ['item' => 'Maximum size: 10MB'],
                        ['item' => 'One file per submission'],
                        ['item' => 'Only .docx files will be accepted'],
                        ['item' => 'File will be automatically converted to PDF after upload'],
                        ['item' => 'Ensure your document is complete before uploading']
                    ]
                ]
            ]
        ]);

        TermsAndConditions::create([
            'type' => 'mop_description_guidelines',
            'title' => 'MOP Description Guidelines',
            'content' => [
                [
                    'title' => 'MOP Description Guidelines',
                    'items' => [
                        ['item' => 'Copy the content from Background & Objectives section of your MoP document'],
                        ['item' => 'Background should explain the current situation or reason for the implementation'],
                        ['item' => 'Objectives should clearly state what will be achieved after implementation'],
                        ['item' => 'Keep the description clear and concise'],
                        ['item' => 'Include any relevant version numbers or system specifications'],
                        ['item' => 'Ensure the description matches with your MoP document content']
                    ]
                ]
            ]
        ]);
    }
}
